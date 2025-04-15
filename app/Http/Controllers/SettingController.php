<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();

        return response()->json([
            'phoneNumber' => $settings->firstWhere('key', 'phone_number')->value,
            'address' => $settings->firstWhere('key', 'address')->value,
            'workingHours' => [
                'trc' => [
                    'days' => $settings->firstWhere('key', 'working_hours_trc_days')->value,
                    'hours' => $settings->firstWhere('key', 'working_hours_trc_hours')->value,
                ],
                'cinema' => [
                    'days' => $settings->firstWhere('key', 'working_hours_cinema_days')->value,
                    'hours' => $settings->firstWhere('key', 'working_hours_cinema_hours')->value,
                ],
            ],
            'socialLinks' => [
                [
                    'telegram' => $settings->firstWhere('key', 'sociallinks_telegram')->value,
                    'vkontakte' => $settings->firstWhere('key', 'sociallinks_vkontakte')->value,
                ],
            ],
            'logos' => [
                'whiteLogo' => $this->storageFullPath($settings->firstWhere('key', 'logo')->value),
                'blackLogo' => $this->storageFullPath($settings->firstWhere('key', 'black_logo')->value),
            ]
        ]);
    }
}
