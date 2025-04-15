<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventLast;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class EventController extends Controller
{
    public function mainIndex()
    {
        $events = Event::mainDisplay()->latest('start_date')->get();

        return response()->json([
            'events' => $events->map(function (Event $event) {
                return [
                    'image' => $this->storageFullPath($event->mainImageDesktop),
                    'alt' => $event->mainImageAlt,
                    'text' => $event->description,
                    'link' => $event->getFrontUrl(),
                ];
            }),
        ]);
    }

    public function index()
    {
        $events = Event::latest('start_date')->get();

        return response()->json([
            'itemsList' => $events->map(function (Event $event) {
                return $this->eventResponse($event);
            })->paginate(request('perPage', 9)),
        ]);
    }

    public function show(Event $event)
    {
        $raw = collect($event->schedule);
        $array = [];

        foreach ($raw as $element) {
            $array[] = [
                ...$element,
                'group' => Arr::get($element, 'fields.start_date')?->startOfDay()->unix()
                    . Arr::get($element, 'fields.end_date')?->startOfDay()->unix()
            ];
        }

        $collection = collect($array);

        $eventsList = $collection
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
            'id' => $event->getKey(),
            'heading' => $event->heading,
            'description' => $event->description,
            'mainImage' => [
                'desktopImage' => $this->storageFullPath($event->mainImageDesktop),
                'mobileImage' => $this->storageFullPath($event->mainImageMobile),
                'alt' => $event->mainImageAlt,
            ],
            'gallery' => collect($event->gallery)->map(function (array $item) {
                return [
                    Arr::get($item, 'fields'),
                    'desktopImage' => $this->storageFullPath(Arr::get($item, 'fields.desktopImage')),
                    'mobileImage' => $this->storageFullPath(Arr::get($item, 'fields.mobileImage')),
                ];
            }),
            'eventsList' => $eventsList,
            'otherEventsList' => $event->similar->isNotEmpty() ? $event->similar->map(function (Event $event) {
                return $this->eventResponse($event);
            }) : Event::whereNot($event->getKeyName(), $event->getKey())->get()->map(function (Event $event) {
                return $this->eventResponse($event);
            }),
        ]);
    }

    public function showEventLast(EventLast $event)
    {
        $raw = collect($event->schedule);
        $array = [];

        foreach ($raw as $element) {
            $array[] = [
                ...$element,
                'group' => Arr::get($element, 'fields.start_date')?->startOfDay()->unix()
                    . Arr::get($element, 'fields.end_date')?->startOfDay()->unix()
            ];
        }

        $collection = collect($array);

        $eventsList = $collection
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
            'id' => $event->getKey(),
            'heading' => $event->heading,
            'description' => $event->description,
            'mainImage' => [
                'desktopImage' => $this->storageFullPath($event->mainImageDesktop),
                'mobileImage' => $this->storageFullPath($event->mainImageMobile),
                'alt' => $event->mainImageAlt,
            ],
            'gallery' => collect($event->gallery)->map(function (array $item) {
                return [
                    Arr::get($item, 'fields'),
                    'desktopImage' => $this->storageFullPath(Arr::get($item, 'fields.desktopImage')),
                    'mobileImage' => $this->storageFullPath(Arr::get($item, 'fields.mobileImage')),
                ];
            }),
            'eventsList' => $eventsList,
            'otherEventsList' => $event->similar->isNotEmpty() ? $event->similar->map(function (Event $event) {
                return $this->eventResponse($event);
            }) : Event::whereNot($event->getKeyName(), $event->getKey())->get()->map(function (Event $event) {
                return $this->eventResponse($event);
            }),
        ]);
    }

    public function eventsLastDate(EventLast $EventLast) {

        $events = EventLast::get();

        return response()->json([
            'itemsList' => $events->map(function (EventLast $event) {
                return $this->eventResponse($event);
            })->paginate(request('perPage', 9)),
        ]);

    }

    public function eventResponse($event): array
    {
        return [
            'id' => $event->getKey(),
            'image' => $this->storageFullPath($event->mainImageDesktop),
            'alt' => $event->mainImageAlt,
            'heading' => $event->heading,
            'link' => $event->getFrontUrl(),
        ];
    }
}
