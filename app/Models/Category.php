<?php

namespace App\Models;

use App\Enums\TenantTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => TenantTypeEnum::class,
    ];

    public function scopeMainDisplay(Builder $query): void
    {
        $query->where('is_main_display', true);
    }

    public function tenants()
    {
        return $this->morphedByMany(Tenant::class, 'stockable');
    }
}
