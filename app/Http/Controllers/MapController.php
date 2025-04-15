<?php

namespace App\Http\Controllers;


use App\Enums\TenantTypeEnum;
use App\Models\Category;
use App\Models\Tenant;
use App\Models\Stock;
use Illuminate\Http\Request;

class MapController extends Controller
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
                $query->where('id', $request->get('subCategory'))
                    ->orWhere('category', 'like', '%' . $request->get('subCategory') . '%');
            });
        }

        if ($request->has('floor')) {
            $query->where('floor', $request->get('floor'));
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

        return response()->json($query->get()->map(function (Tenant $tenant) {
            return [
                'id' => $tenant->getKey(),
                'idSpace' => $tenant->idSpace,
                'category' => $tenant->type,
                'subCategory' => $tenant->categories->map(function (Category $category) {
                    return [
                        'id' => $category->getKey(),
                        'category' => $category->category,
                        'link' => route('categories.show', $category, false),
                    ];
                }),
                'logo' => $this->storageFullPath($tenant->logo),
                'alt' => $tenant->mainImageAlt,
                'name' => $tenant->storeName,
                'description' => $tenant->description,
                'floor' => $tenant->floor,
                'link' => $tenant->getFrontUrl(),
                'new' => $tenant->new,
                'has_stocks' => $tenant->has_stocks === 'auto'
                    ? $tenant->has_stocks_auto
                    : $tenant->has_stocks === 'yes',
            ];
        }));
    }
}
