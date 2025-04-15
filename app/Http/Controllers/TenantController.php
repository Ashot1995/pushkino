<?php

namespace App\Http\Controllers;


use App\Enums\TenantTypeEnum;
use App\Models\Category;
use App\Models\Stock;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::query();

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

            $query->where('type', $type);
        }

        if ($request->has('subCategory')) {
            $query->whereHas('categories', function ($query) use ($request){
                $query->whereIn('id', explode(',', $request->get('subCategory')))
                    ->orWhere('category', 'like', '%' . $request->get('subCategory') . '%');
            });
        }

        if ($request->has('floor')) {
            $query->whereIn('floor', explode(',', $request->get('floor')));
        }

        if ($request->has('new')) {
            $query->where('new', $request->boolean('new'));
        }

        if ($request->has('has_stocks')) {
            $now = now();

            if ($request->boolean('has_stocks')) {
                $query
                    ->where('has_stocks', 'auto')
                    ->whereHas('stocks', function ($query) use ($request, $now){
                    $query->whereDate('start_date', '<=', $now)
                        ->whereDate('end_date', '>=', $now);
                })->orWhere('has_stocks', 'yes');
            } else {
                $query
                    ->where('has_stocks', 'auto')
                    ->whereHas('stocks', function ($query) use ($request, $now){
                    $query->whereDate('start_date', '>', $now)
                        ->whereDate('end_date', '<', $now);
                })->orDoesntHave('stocks')
                    ->orWhere('has_stocks', 'no');
            }
        }

        return response()->json([
            'storesList' => $query->get()->map(function (Tenant $tenant) {
                return $this->tenantResponse($tenant);
            }),
        ]);
    }

    public function show(string $slug)
    {
        $tenant = Tenant::where('slug', $slug)->firstOrFail();

        return response()->json([
            'id' => $tenant->getKey(),
            'type' => $tenant->trans_type,
            'sort' => $tenant->sort,
            'slug' => $tenant->slug,
            'idSpace' => $tenant->idSpace,
            'logo' => $this->storageFullPath($tenant->logo),
            'mainImages' => [
                'desktop' => $this->storageFullPath($tenant->mainImageDesktop),
                'mobile' => $this->storageFullPath($tenant->mainImageMobile),
                'alt' => $tenant->mainImageAlt,
            ],
            'storeName' => $tenant->storeName,
            'floor' => $tenant->floor,
            'description' => $tenant->description,
            'workingTime' => $tenant->workingTime,
            'categoriesList' => $tenant->categories->map(function (Category $category) {
                return [
                    'id' => $category->getKey(),
                    'category' => $category->category,
                    'link' => route('categories.show', $category, false),
                ];
            }),
            'links' => collect($tenant->links)->map(function (array $item) {
                return [
                    ...Arr::get($item, 'fields'),
                ];
            }),
            'phoneNumber' => $tenant->phoneNumber,
            'gallery' => collect($tenant->gallery)->map(function (array $item) {
                return [
                    ...Arr::get($item, 'fields'),
                    'desktopImage' => $this->storageFullPath(Arr::get($item, 'fields.desktopImage')),
                    'mobileImage' => $this->storageFullPath(Arr::get($item, 'fields.mobileImage')),
                ];
            }),
            'stocksList' => $tenant->stocks->map(function (Stock $stock) {
                return [
                    'image' => $this->storageFullPath($stock->mainBannerDesktop),
                    'heading' => $stock->heading,
                    'description' => $stock->description,
                    'link' => $stock->getFrontUrl(),
                ];
            }),
            'similarStoresList' => $tenant->similar->isNotEmpty() ? $tenant->similar->map(function (Tenant $tenant) {
                return $this->tenantResponse($tenant);
            }) : Tenant::whereNot($tenant->getKeyName(), $tenant->getKey())->limit(10)->get()->map(function (Tenant $tenant) {
                return $this->tenantResponse($tenant);
            }),
            'new' => $tenant->new,
            'has_stocks' => $tenant->has_stocks === 'auto'
                ? $tenant->has_stocks_auto
                : $tenant->has_stocks === 'yes',
        ]);
    }

    private function tenantResponse(Tenant $tenant): array
    {
        return [
            'id' => $tenant->getKey(),
            'type' => $tenant->trans_type,
            'slug' => $tenant->slug,
            'idSpace' => $tenant->idSpace,
            'liter' => $tenant->liter,
            'logo' => $this->storageFullPath($tenant->logo),
            'alt' => $tenant->mainImageAlt, // todo: откуда это поле?
            'heading' => $tenant->storeName,
            'category' => $tenant->categories->map(function (Category $category) {
                return [
                    'id' => $category->getKey(),
                    'category' => $category->category,
                    'link' => route('categories.show', $category, false),
                ];
            }),
            'floor' => $tenant->floor,
            'link' => $tenant->getFrontUrl(),
            'stocks' => $tenant->stocks->map(function (Stock $stock) {
                return [
                    'id' => $stock->getKey(),
                    'stockType' => $stock->type,
                    'backgroundButton' => null, // todo: откуда это поле?
                ];
            }),
            'new' => $tenant->new,
            'has_stocks' => $tenant->has_stocks === 'auto'
                ? $tenant->has_stocks_auto
                : $tenant->has_stocks === 'yes',
        ];
    }
}
