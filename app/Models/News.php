<?php

namespace App\Models;

use App\Traits\RepeaterFixTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    use RepeaterFixTrait;

    protected $casts = [
        'published_date' => 'date',
        'otherAdditionalBanners' => 'json',
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
    ];

    public function tenants()
    {
        return $this->morphToMany(Tenant::class, 'tenantable');
    }

    public function scopeMainDisplay(Builder $query): void
    {
        $query->where('is_main_display', true);
    }

    public function similar()
    {
        return $this->belongsToMany($this::class, 'news_similar_news', 'similar_news_id', 'news_id');
    }

    public function getFrontUrl()
    {
        return '/news-and-events/news/' . self::getKey();
    }
}
