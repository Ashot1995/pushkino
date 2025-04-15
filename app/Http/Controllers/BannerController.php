<?php

namespace App\Http\Controllers;

use App\Models\Banner;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::active()->get();

        return response()->json([
            'bannersList' => $banners->map(function (Banner $banner) {
                $result = [
                    'template' => $banner->template,
                ];

                switch ($banner->template) {
                    case 'two oval pictures':
                        $result = [
                            ...$result,
                            'images' => [
                                'firstImage' => $this->firstImage($banner),
                                'secondImage' => $this->secondImage($banner),
                            ],
                            ...$this->detail($banner),
                        ];

                        break;
                    case 'one square picture':
                        $result = [
                            ...$result,
                            'images' => $this->firstImage($banner),
                            ...$this->detail($banner),
                        ];

                        break;
                    case 'two round pictures':
                        $result = [
                            ...$result,
                            'images' => [
                                'firstImage' => $this->firstImage($banner),
                                'secondImage' => $this->secondImage($banner),
                            ],
                            ...$this->detail($banner),
                        ];

                        break;
                    case 'one full picture':
                        $result = [
                            ...$result,
                            'images' => $this->firstImage($banner),
                            ...$this->detail($banner),
                        ];

                        break;
                }

                return $result;
            }),
        ]);
    }

    private function firstImage(Banner $banner): array
    {
        return [
            'imageDesktop' => $this->storageFullPath($banner->firstImageDesktop),
            'imageMobile' => $this->storageFullPath($banner->firstImageMobile),
            'alt' => $banner->firstImageAlt,
        ];
    }

    private function secondImage(Banner $banner): array
    {
        return [
            'imageDesktop' => $this->storageFullPath($banner->secondImageDesktop),
            'imageMobile' => $this->storageFullPath($banner->secondImageMobile),
            'alt' => $banner->secondImageAlt,
        ];
    }

    private function detail(Banner $banner): array
    {
        return [
            'heading' => $banner->heading,
            'description' => $banner->description,
            'buttonText' => $banner->buttonText,
            'link' => $banner->link,
            'backgroundColor' => $banner->backgroundColor,
            'colorText' => $banner->colorText,
            'backgroundColorButton' => $banner->backgroundColorButton,
            'colorButtonText' => $banner->colorButtonText,
        ];
    }
}
