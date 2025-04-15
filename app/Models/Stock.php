<?php

namespace App\Models;

use App\Traits\ActiveScopeTrait;
use App\Traits\RepeaterFixTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    use RepeaterFixTrait;
    use ActiveScopeTrait;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'otherAdditionalBanners' => 'json',
        'linksToLanding' => 'json',
        'gallery' => 'json',
    ];

    protected $repeaters = [
        'otherAdditionalBanners' => [
            'desktopImage',
            'mobileImage',
        ],
        'gallery' => [
            'desktopImage',
            'mobileImage',
        ],
        'linksToLanding' => [
            'image',
        ],
    ];

    public function tenants()
    {
        return $this->morphedByMany(Tenant::class, 'stockable');
    }

    public function scopeMainDisplay(Builder $query): void
    {
        $query->where('is_main_display', true);
    }

    public function similar()
    {
        return $this->belongsToMany($this::class, 'stock_similar_stock', 'similar_stock_id', 'stock_id');
    }

    public function getFrontUrl()
    {
        return '/stocks/' . self::getKey();
    }
}
