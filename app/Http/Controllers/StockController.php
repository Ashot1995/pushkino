<?php

namespace App\Http\Controllers;

use App\Enums\TenantTypeEnum;
use App\Models\Category;
use App\Models\Stock;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class StockController extends Controller
{
    public function mainIndex()
    {
        $stocks = Stock::active()->mainDisplay()->latest('start_date')->get();

        return response()->json([
            'stocksList' => $stocks->map(function (Stock $stock) {
                return [
                    'image' => $this->storageFullPath($stock->ovalBanner) ?: $this->storageFullPath($stock->mainBannerDesktop),
                    'alt' => $stock->ovalBannerAlt,
                    'text' => $stock->ovalBannerText ?: $stock->heading,
                    'link' => $stock->getFrontUrl(),
                ];
            }),
        ]);
    }

    public function index(Request $request)
    {
        if ($request->get('direction', 'asc') === 'desc') {
            $query = Stock::active()->oldest($request->get('by', 'start_date'));
        } else {
            $query = Stock::active()->latest($request->get('by', 'start_date'));
        }

        if ($request->has('category')) {
            switch ($request->get('category')) {
                case 'shops':
                    $type = TenantTypeEnum::Shop;
                    break;
                case 'cafes':
                    $type = TenantTypeEnum::Restaurant;
                    break;
                case 'recreation':
                    $type = TenantTypeEnum::Entertainment;
                    break;
                case 'services':
                    $type = TenantTypeEnum::Service;
                    break;
                default:
                    $type = $request->get('type');
            }

            $query->whereHas('tenants', function ($query) use ($request, $type){
                $query->whereIn('type', explode(',', $type instanceof TenantTypeEnum ? $type->value : $type));
            });
        }

        if ($request->has('subCategory')) {
            $query->whereHas('tenants', function ($query) use ($request){
                $query->whereHas('categories', function ($query) use ($request){
                    $query->whereIn('id', explode(',', $request->get('subCategory')))
                        ->orWhere('category', 'like', '%' . $request->get('subCategory') . '%');
                });
            });
        }

        return response()->json([
            'stocks' => $query->paginate(request('perPage', 10))->map(function (Stock $stock) {
                return $this->stockResponse($stock);
            }),
        ]);
    }

    public function show(Stock $stock)
    {
        return response()->json([
            'id' => $stock->getKey(),
            'heading' => $stock->heading,
            'date' => Carbon::parse($stock->start_date)?->translatedFormat('d M') . ' - ' . $stock->end_date?->translatedFormat('d M'),
            'description' => $stock->description,
            'mainBanner' => [
                'desktopImage' => $this->storageFullPath($stock->mainBannerDesktop),
                'mobileImage' => $this->storageFullPath($stock->mainBannerMobile),
                'alt' => $stock->mainBannerAlt,
            ],
            'additionalBanner' => [
                'desktopImage' => $this->storageFullPath($stock->additionalBannerDesktop),
                'mobileImage' => $this->storageFullPath($stock->additionalBannerMobile),
                'alt' => $stock->additionalBannerAlt,
            ],
            'otherAdditionalBannersList' => collect($stock->otherAdditionalBanners)->map(function (array $item) {
                return [
                    ...Arr::get($item, 'fields'),
                    'desktopImage' => $this->storageFullPath(Arr::get($item, 'fields.desktopImage')),
                    'mobileImage' => $this->storageFullPath(Arr::get($item, 'fields.mobileImage')),
                ];
            }),
            'linksToLanding' => collect($stock->linksToLanding)->map(function (array $item) {
                return [
                    ...Arr::get($item, 'fields'),
                    'image' => $this->storageFullPath(Arr::get($item, 'fields.image')),
                ];
            }),
            'partners' => $stock->tenants->map(function (Tenant $tenant) {
                return [
                    'id' => $tenant->getKey(),
                    'idSpace' => $tenant->idSpace,
                    'type' => $tenant->type,
                    'logo' => $this->storageFullPath($tenant->logo),
                    'alt' => $tenant->mainImageAlt,
                    'heading' => $tenant->storeName,
                    'floor' => $tenant->floor,
                    'category' => $tenant->categories->map(function (Category $category) {
                        return [
                            'id' => $category->getKey(),
                            'category' => $category->category,
                            'link' => route('categories.show', $category, false),
                        ];
                    }),
                    'new' => $tenant->new,
                    'has_stocks' => $tenant->has_stocks === 'auto'
                        ? $tenant->has_stocks_auto
                        : $tenant->has_stocks === 'yes',
                    'link' => $tenant->getFrontUrl(),
                ];
            }),
            'gallery' => collect($stock->gallery)->map(function (array $item) {
                return [
                    ...Arr::get($item, 'fields'),
                    'desktopImage' => $this->storageFullPath(Arr::get($item, 'fields.desktopImage')),
                    'mobileImage' => $this->storageFullPath(Arr::get($item, 'fields.mobileImage')),
                ];
            }),
            'otherStocksList' => $stock->similar->isNotEmpty() ? $stock->similar->map(function (Stock $stock) {
                return $this->stockResponse($stock);
            }) : Stock::whereNot($stock->getKeyName(), $stock->getKey())->limit(10)->get()->map(function (Stock $stock) {
                return $this->stockResponse($stock);
            }),
        ]);
    }

    private function stockResponse(Stock $stock): array
    {
        return [
            'id' => $stock->getKey(),
            'image' => $this->storageFullPath($stock->mainBannerDesktop),
            'alt' => $stock->mainBannerAlt,
            'storeName' => $stock->storeName ?: implode(', ', $stock->tenants?->pluck('storeName')->toArray() ?: []),
            'heading' => $stock->heading,
            'date' => $stock->start_date?->translatedFormat('d M') . ' - ' . $stock->end_date?->translatedFormat('d M'),
            'link' => $stock->getFrontUrl(),
        ];
    }
}
