<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\News;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class NewsController extends Controller
{
    public function mainIndex()
    {
        $news = News::mainDisplay()->latest('published_date')->get();

        return response()->json([
            'news' => $news->map(function (News $news) {
                return [
                    'image' => $this->storageFullPath($news->mainImageDesktop),
                    'alt' => $news->mainImageAlt,
                    'text' => $news->description,
                    'link' => $news->getFrontUrl(),
                ];
            }),
        ]);
    }

    public function index()
    {
        $news = News::latest('published_date')->get();

        return response()->json([
            'itemsList' => $news->map(function (News $news) {
                return $this->newsResponse($news);
            })->paginate(request('perPage', 9)),
        ]);
    }

    public function show(News $news)
    {
        return response()->json([
            'id' => $news->getKey(),
            'heading' => $news->heading,
            'date' => Carbon::parse($news->published_date)?->translatedFormat('d F Y'),
            'description' => $news->description,
            'mainImage' => [
                'desktopImage' => $this->storageFullPath($news->mainImageDesktop),
                'mobileImage' => $this->storageFullPath($news->mainImageMobile),
                'alt' => $news->mainImageAlt,
            ],
            'gallery' => collect($news->gallery)->map(function (array $item) {
                return [
                    ...Arr::get($item, 'fields'),
                    'desktopImage' => $this->storageFullPath(Arr::get($item, 'fields.desktopImage')),
                    'mobileImage' => $this->storageFullPath(Arr::get($item, 'fields.mobileImage')),
                ];
            }),
            'otherAdditionalBannersList' => collect($news->otherAdditionalBanners)->map(function (array $item) {
                return [
                    ...Arr::get($item, 'fields'),
                    'desktopImage' => $this->storageFullPath(Arr::get($item, 'fields.desktopImage')),
                    'mobileImage' => $this->storageFullPath(Arr::get($item, 'fields.mobileImage')),
                ];
            }),
            'partners' => $news->tenants->map(function (Tenant $tenant) {
                return [
                    'id' => $tenant->getKey(),
                    'type' => $tenant->type,
                    'logo' => $this->storageFullPath($tenant->logo),
                    'alt' => $tenant->mainImageAlt,
                    'heading' => $tenant->storeName,
                    'floor' => $tenant->floor,
                    'link' => $tenant->getFrontUrl(),
                ];
            }),
            'otherNewsList' => $news->similar->isNotEmpty() ? $news->similar->map(function (News $news) {
                return $this->newsResponse($news);
            }) : News::whereNot($news->getKeyName(), $news->getKey())->get()->map(function (News $news) {
                return $this->newsResponse($news);
            }),
        ]);
    }

    public function newsResponse(News $news): array
    {
        return [
            'id' => $news->getKey(),
            'image' => $this->storageFullPath($news->mainImageDesktop),
            'alt' => $news->mainImageAlt,
            'heading' => $news->heading,
            'link' => $news->getFrontUrl(),
        ];
    }
}
