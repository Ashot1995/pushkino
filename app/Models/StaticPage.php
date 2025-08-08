<?php

namespace App\Models;

use App\Traits\RepeaterFixTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class StaticPage extends Model
{
    use HasFactory;
    use RepeaterFixTrait;

    protected $casts = [
        'fields' => 'json',

        // О ТРЦ

        'aboutTextBanners' => 'json',
        'aboutAdvantages' => 'json',
        'aboutGallery' => 'json',

        // Контакты

        'contactsAdminPhone' => 'json',
        'contactsOtherContactsList' => 'json',
        'contactsRentManagers' => 'json',

        // Арендаторам

        'tenantsAdvantages' => 'json',
        'tenantsContacts' => 'json',
    ];

    protected $appends = [
        'textLayout'
    ];

    protected $repeaters = [
        'aboutTextBanners' => [
            'desktopImage',
            'mobileImage',
        ],
        'aboutGallery' => [
            'desktopImage',
            'mobileImage',
        ],
        'tenantsContacts' => [
            'presentation',
        ],
    ];

    public function getTextLayoutAttribute()
    {
        if (isset(\App\Nova\StaticPage::$layouts[$this->layout])) {
            return \App\Nova\StaticPage::$layouts[$this->layout];
        }

        return $this->layout;
    }

    public function getFieldsAttribute($value)
    {
        $value = json_decode($value, true);

        if(isset($value['date'])) {
            $value['date'] = Carbon::parse($value['date']);
        }

        return $value;
    }

    public function setFieldsAttribute($value)
    {
        if(isset($value['date'])) {
            $value['date'] = Carbon::parse($value);
        }

        $this->attributes['fields'] = $value;
    }

    public function getFrontUrl()
    {
        return '/' . $this->slug;
    }
}
