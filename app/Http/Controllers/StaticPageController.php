<?php

namespace App\Http\Controllers;


use App\Models\StaticPage;
use App\Models\Tenant;
use Illuminate\Support\Arr;

class StaticPageController extends Controller
{
    public function show(string $slug)
    {
        $staticPage = StaticPage::where('slug', $slug)->firstOrFail();

        return response()->json($this->resolve($staticPage));
    }

    private function resolve(StaticPage $staticPage)
    {
        if (method_exists($this, $staticPage->layout)) {
            return $this->{$staticPage->layout}($staticPage);
        }

        return [];
    }

    private function about(StaticPage $staticPage)
    {
        return [
            'metaTitle' => Arr::get($staticPage->fields, 'metaTitle'),
            'metaDescription' => Arr::get($staticPage->fields, 'metaDescription'),
            'metaKeywords' => Arr::get($staticPage->fields, 'metaKeywords'),
            'heading' => Arr::get($staticPage->fields, 'heading'),
            'description' => Arr::get($staticPage->fields, 'description'),
            'presentationLink' => $this->storageFullPath(Arr::get($staticPage->fields, 'presentation')),
            'banners' => [
                'mainBanner' => [
                    'desktopImage' => $this->storageFullPath(Arr::get($staticPage->fields, 'mainBannerDesktop')),
                    'mobileImage' => $this->storageFullPath(Arr::get($staticPage->fields, 'mainBannerMobile')),
                    'alt' =>Arr::get($staticPage->fields, 'mainBannerAlt'),
                ],
                'additionalBanner' => [
                    'desktopImage' => $this->storageFullPath(Arr::get($staticPage->fields, 'additionalBannerDesktop')),
                    'mobileImage' => $this->storageFullPath(Arr::get($staticPage->fields, 'additionalBannerMobile')),
                    'alt' =>Arr::get($staticPage->fields, 'additionalBannerAlt'),
                ],
                'bannersWithTextList' => collect($staticPage->aboutTextBanners)->map(function (array $item) {
                    return [
                        ...Arr::get($item, 'fields'),
                        'desktopImage' => $this->storageFullPath(Arr::get($item, 'fields.desktopImage')),
                        'mobileImage' => $this->storageFullPath(Arr::get($item, 'fields.mobileImage')),
                    ];
                }),
            ],
            'advantages' => collect($staticPage->aboutAdvantages)->map(function (array $item) {
                return [
                    ...Arr::get($item, 'fields'),
                ];
            }),
            'sliderWithDescription' => [
                'heading' => Arr::get($staticPage->fields, 'sliderWithDescriptionHeading'),
                'description' => Arr::get($staticPage->fields, 'sliderWithDescriptionDescription'),
                'images' => collect($staticPage->aboutGallery)->map(function (array $item) {
                    return [
                        ...Arr::get($item, 'fields'),
                        'desktopImage' => $this->storageFullPath(Arr::get($item, 'fields.desktopImage')),
                        'mobileImage' => $this->storageFullPath(Arr::get($item, 'fields.mobileImage')),
                    ];
                }),
            ],
            'storesList' => [
                'parking' => Tenant::where('floor', 0)->get()->map(function (Tenant $tenant) {
                    return [
                        'image' => $this->storageFullPath($tenant->logo),
                        'alt' => $tenant->storeName,
                        'storeName' => $tenant->storeName,
                        'link' => $tenant->getFrontUrl(),
                    ];
                }),
                'firstFloor' => Tenant::where('floor', 1)->get()->map(function (Tenant $tenant) {
                    return [
                        'image' => $this->storageFullPath($tenant->logo),
                        'alt' => $tenant->storeName,
                        'storeName' => $tenant->storeName,
                        'link' => $tenant->getFrontUrl(),
                    ];
                }),
                'secondFloor' => Tenant::where('floor', 2)->get()->map(function (Tenant $tenant) {
                    return [
                        'image' => $this->storageFullPath($tenant->logo),
                        'alt' => $tenant->storeName,
                        'storeName' => $tenant->storeName,
                        'link' => $tenant->getFrontUrl(),
                    ];
                })
            ],
        ];
    }

    private function rules(StaticPage $staticPage)
    {
        return [
            'metaTitle' => Arr::get($staticPage->fields, 'metaTitle'),
            'metaDescription' => Arr::get($staticPage->fields, 'metaDescription'),
            'metaKeywords' => Arr::get($staticPage->fields, 'metaKeywords'),
            'heading' => Arr::get($staticPage->fields, 'heading'),
            'desktopImage' => $this->storageFullPath(Arr::get($staticPage->fields, 'desktopImage')),
            'mobileImage' => $this->storageFullPath(Arr::get($staticPage->fields, 'mobileImage')),
            'alt' => Arr::get($staticPage->fields, 'alt'),
            'version' => Arr::get($staticPage->fields, 'version'),
            'date' => Arr::get($staticPage->fields, 'date')?->translatedFormat('d F Y'),
            'link' => $this->storageFullPath(Arr::get($staticPage->fields, 'document')),
        ];
    }

    private function contacts(StaticPage $staticPage)
    {
        return [
            'metaTitle' => Arr::get($staticPage->fields, 'metaTitle'),
            'metaDescription' => Arr::get($staticPage->fields, 'metaDescription'),
            'metaKeywords' => Arr::get($staticPage->fields, 'metaKeywords'),
            'heading' => Arr::get($staticPage->fields, 'heading'),
            'administration' => [
                'phoneNumbers' => collect($staticPage->contactsAdminPhone)->map(function (array $item) {
                    return Arr::get($item, 'fields.phone');
                }),
                'email' => Arr::get($staticPage->fields, 'adminEmail'),
            ],
            'rentalPremises' => [
                'phoneNumber' => Arr::get($staticPage->fields, 'rentManagerPhone'),
                'managerName' => Arr::get($staticPage->fields, 'rentManagerName'),
                'managerEmail' => Arr::get($staticPage->fields, 'rentManagerEmail'),
            ],
            'marketing' => [
                'phoneNumber' => Arr::get($staticPage->fields, 'adManagerPhone'),
                'managerName' => Arr::get($staticPage->fields, 'adManagerName'),
                'managerEmail' => Arr::get($staticPage->fields, 'adManagerEmail'),
            ],
            'otherContactsList' => collect($staticPage->contactsOtherContactsList)->map(function (array $item) {
                return [
                    ...Arr::get($item, 'fields'),
                ];
            }),
        ];
    }

    private function tenants(StaticPage $staticPage)
    {
        return [
            'metaTitle' => Arr::get($staticPage->fields, 'metaTitle'),
            'metaDescription' => Arr::get($staticPage->fields, 'metaDescription'),
            'metaKeywords' => Arr::get($staticPage->fields, 'metaKeywords'),
            'heading' => Arr::get($staticPage->fields, 'heading'),
            'advantagesList' => collect($staticPage->tenantsAdvantages)->map(function (array $item) {
                return [
                    ...Arr::get($item, 'fields'),
                ];
            }),
            'mainBanner' => [
                'desktopImage' => $this->storageFullPath(Arr::get($staticPage->fields, 'mainBannerDesktop')),
                'mobileImage' => $this->storageFullPath(Arr::get($staticPage->fields, 'mainBannerMobile')),
                'alt' =>Arr::get($staticPage->fields, 'mainBannerAlt'),
                'description' =>Arr::get($staticPage->fields, 'mainBannerDescription'),
                'buttonLink' =>Arr::get($staticPage->fields, 'mainBannerButtonLink'),
                'buttonText' =>Arr::get($staticPage->fields, 'mainBannerButtonText'),
            ],
            'contactsList' => collect($staticPage->tenantsContacts)->map(function (array $item) {
                return [
                    ...Arr::get($item, 'fields'),
                    'presentationLink' => $this->storageFullPath(Arr::get($item, 'fields.presentation')),
                ];
            }),
        ];
    }
}
