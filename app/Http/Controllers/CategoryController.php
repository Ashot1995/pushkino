<?php

namespace App\Http\Controllers;


use App\Enums\TenantTypeEnum;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function mainIndex(Request $request)
    {
        return response()->json([
            'categoryList' => Category::mainDisplay()
                ->where('type', TenantTypeEnum::Shop)
                ->get()
                ->map(function (Category $category) {
                    return [
                        'id' => $category->getKey(),
                        'category' => $category->category,
                        'image' => $this->storageFullPath($category->image),
                        'alt' => $category->imageAlt,
                        'link' => route('categories.show', $category, false),
                    ];
                }),
        ]);
    }

    public function index(Request $request)
    {
        if ($request->has('type')) {
            switch ($request->get('type')) {
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

            $categories = Category::where('type', $type)->get();
        } else {
            $categories = Category::all();
        }

        return response()->json([
            'categories' => [
                'shops' => [
                    'name' => 'Магазины',
                    'categories' => $categories->where('type', TenantTypeEnum::Shop)->map(function (Category $category) {
                        return [
                            'id' => $category->getKey(),
                            'category' => $category->category,
                        ];
                    })->values(),
                ],
                'cafes' => [
                    'name' => 'Кафе и рестораны',
                    'categories' => $categories->where('type', TenantTypeEnum::Restaurant)->map(function (Category $category) {
                        return [
                            'id' => $category->getKey(),
                            'category' => $category->category,
                        ];
                    })->values(),
                ],
                'recreation' => [
                    'name' => 'Развлечения',
//                    'categories' => $categories->where('type', TenantTypeEnum::Entertainment)->map(function (Category $category) {
//                        return [
//                            'id' => $category->getKey(),
//                            'category' => $category->category,
//                        ];
//                    })->values(),
                ],
                'services' => [
                    'name' => 'Услуги',
                    'categories' => $categories->where('type', TenantTypeEnum::Service)->map(function (Category $category) {
                        return [
                            'id' => $category->getKey(),
                            'category' => $category->category,
                        ];
                    })->values(),
                ],
            ],
        ]);
    }

    public function show(Category $category)
    {
        return response()->json([
            'id' => $category->getKey(),
            'category' => $category->category,
        ]);
    }
}
