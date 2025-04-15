<?php

namespace App\Models;

use App\Enums\TenantTypeEnum;
use App\Traits\RepeaterFixTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;
    use RepeaterFixTrait;

    protected $casts = [
        'type' => TenantTypeEnum::class,
        'links' => 'json',
        'gallery' => 'json',
        'new' => 'boolean',
    ];

    protected $appends = [
        'has_stocks_auto',
        'trans_type',
    ];

    protected $repeaters = [
        'gallery' => [
            'desktopImage',
            'mobileImage',
        ],
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function stocks()
    {
        return $this->morphToMany(Stock::class, 'stockable');
    }

    public function news()
    {
        return $this->morphedByMany(News::class, 'tenantable');
    }

    public function events()
    {
        return $this->morphedByMany(Event::class, 'tenantable');
    }

    public function similar()
    {
        return $this->belongsToMany($this::class, 'tenant_similar_tenant', 'similar_tenant_id', 'tenant_id');
    }

    public function getHasStocksAutoAttribute()
    {
        $now = now();

        return $this
            ->stocks()
            ->whereDate('start_date', '<=', $now)
            ->whereDate('end_date', '>=', $now)
            ->exists();
    }

    public function getTransTypeAttribute()
    {
        if ($this->type->value === 'Магазины') {
            return 'shops';
        }

        if ($this->type->value === 'Кафе и рестораны') {
            return 'cafes';
        }

        if ($this->type->value === 'Услуги и сервисы') {
            return 'services';
        }

        if ($this->type->value === 'Развлечения') {
            return 'recreation';
        }

        return null;
    }

    public function getFrontUrl()
    {
        if ($this->type === TenantTypeEnum::Shop) {
            return '/stores/' . $this->slug;
        }

        if ($this->type === TenantTypeEnum::Restaurant) {
            return '/cafes/' . $this->slug;
        }

        if ($this->type === TenantTypeEnum::Service) {
            return '/services/' . $this->slug;
        }

        if ($this->type === TenantTypeEnum::Entertainment) {
            return '/recreation/' . $this->slug;
        }

        return null;
    }
}
