<?php

namespace App\Http\Controllers;

use App\Models\EventLast;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class EventLastController extends Controller
{
    public function mainIndex()
    {
        $EventLasts = EventLast::get();

        return response()->json([
            'events' => $EventLasts->map(function (EventLast $EventLast) {
                return [
                    'image' => $this->storageFullPath($EventLast->mainImageDesktop),
                    'alt' => $EventLast->mainImageAlt,
                    'text' => $EventLast->description,
                    'link' => $EventLast->getFrontUrl(),
                ];
            }),
        ]);
    }

    public function index()
    {
        $EventLasts = EventLast::get();

        return response()->json([
            'itemsList' => $EventLasts->map(function (EventLast $EventLast) {
                return $this->eventResponse($EventLast);
            })->paginate(request('perPage', 9)),
        ]);
    }

    public function show(EventLast $EventLast)
    {
        $raw = collect($EventLast->schedule);
        $array = [];

        foreach ($raw as $element) {
            $array[] = [
                ...$element,
                'group' => Arr::get($element, 'fields.start_date')?->startOfDay()->unix()
                    . Arr::get($element, 'fields.end_date')?->startOfDay()->unix()
            ];
        }

        $collection = collect($array);

        $EventLastsList = $collection
            ->sortBy('fields.start_date')
            ->groupBy('group')
            ->map(function (Collection $group) {
                if (
                    is_null(Arr::get($group->first(), 'fields.end_date'))
                    || Arr::get($group->first(), 'fields.start_date')->startOfDay()->equalTo(
                        Arr::get($group->first(), 'fields.end_date')->startOfDay()
                    )
                ) {
                    $date = Arr::get($group->first(), 'fields.start_date')?->translatedFormat('d F');
                } else {
                    $date = Arr::get($group->first(), 'fields.start_date')?->translatedFormat('d F')
                        . ' - '
                        . Arr::get($group->first(), 'fields.end_date')?->translatedFormat('d F');
                }

                return [
                    'date' => $date,
                    'events' => $group->map(function (array $element) {
                        return [
                            'time' => Arr::get($element, 'fields.time'),
                            'description' => Arr::get($element, 'fields.description'),
                            'cost' => Arr::get($element, 'fields.cost'),
                        ];
                    }),
                ];
            })->values();

        return response()->json([
            'id' => $EventLast->getKey(),
            'heading' => $EventLast->heading,
            'description' => $EventLast->description,
            'mainImage' => [
                'desktopImage' => $this->storageFullPath($EventLast->mainImageDesktop),
                'mobileImage' => $this->storageFullPath($EventLast->mainImageMobile),
                'alt' => $EventLast->mainImageAlt,
            ],
            'gallery' => collect($EventLast->gallery)->map(function (array $item) {
                return [
                    ...Arr::get($item, 'fields'),
                    'desktopImage' => $this->storageFullPath(Arr::get($item, 'fields.desktopImage')),
                    'mobileImage' => $this->storageFullPath(Arr::get($item, 'fields.mobileImage')),
                ];
            }),
            'eventsList' => $EventLastsList,
            'otherEventsList' => $EventLast->similar->isNotEmpty() ? $EventLast->similar->map(function (EventLast $EventLast) {
                return $this->eventResponse($EventLast);
            }) : EventLast::whereNot($EventLast->getKeyName(), $EventLast->getKey())->get()->map(function (EventLast $EventLast) {
                return $this->eventResponse($EventLast);
            }),
        ]);
    }

    public function eventResponse(EventLast $EventLast): array
    {
        return [
            'id' => $EventLast->getKey(),
            'image' => $this->storageFullPath($EventLast->mainImageDesktop),
            'alt' => $EventLast->mainImageAlt,
            'heading' => $EventLast->heading,
            'link' => $EventLast->getFrontUrl(),
        ];
    }
}
