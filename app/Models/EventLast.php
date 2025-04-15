<?php

namespace App\Models;

use App\Traits\RepeaterFixTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class EventLast extends Model
{
    use HasFactory;
    use RepeaterFixTrait;

    protected $casts = [
        'start_date' => 'date',
        'otherAdditionalBanners' => 'json',
        'schedule' => 'json',
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

    public function getScheduleAttribute()
    {
        $schedule = collect(json_decode(Arr::get($this->attributes, 'schedule', '[]'), true));

        return $schedule->map(function (array $element) {
            return [
                ...$element,
                'fields' => [
                    ...Arr::get($element, 'fields'),
                    'start_date' => Carbon::createFromFormat('Y-m-d', Arr::get($element, 'fields.start_date')),
                    'end_date' => Arr::get($element, 'fields.end_date') !== null
                    ? Carbon::createFromFormat('Y-m-d', Arr::get($element, 'fields.end_date'))
                    : null,
                ],
            ];
        });
    }

    public function similar()
    {
        return $this->belongsToMany($this::class, 'event_last_similar_event', 'similar_event_last_id', 'event_last_id');
    }

    public function getFrontUrl()
    {
        return '/news-and-events/event-lasts/' . self::getKey();
    }
}
