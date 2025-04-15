<?php

namespace App\Nova\Actions;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use App\Models\News;
use App\Models\Event;
use App\Models\EventLast;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ActionFields;
use Throwable;

class MoveToEventLast extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * The displayable name of the action.
     *
     * @var \Stringable|string
     */
    public $name = 'Move To Event Last';

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return __($this->name);
    }

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $eventLasts = new EventLast();

        foreach ($models as $model) {
            $attributes = collect([$model])->map->getAttributes()->all();
            $attributes = reset($attributes);

            try {
                $eventLasts->forceFill(
                    [
                        'heading' => $attributes['heading'],
                        'start_date' => $attributes['start_date'] ?? ($attributes['published_date'] ?? null),
                        'description' => $attributes['description'],
                        'mainImageDesktop' => $attributes['mainImageDesktop'],
                        'mainImageMobile' => $attributes['mainImageMobile'],
                        'mainImageAlt' => $attributes['mainImageAlt'],
                        'otherAdditionalBanners' => (!empty($attributes['otherAdditionalBanners'])
                            ? json_decode(
                                str_replace('news-other-additional-banners-item','event-other-additional-banners-item',$attributes['otherAdditionalBanners']), true)
                            : null
                        ),
                        'schedule' => (!empty($attributes['schedule'])
                            ? json_decode($attributes['schedule'], true)
                            : null
                        ),
                        'gallery' => (!empty($attributes['gallery'])
                            ? json_decode(
                                str_replace('news-gallery-item','event-gallery-item', $attributes['gallery']), true)
                            : null
                        ),
                    ]
                );

                $eventLasts->save();
                if ($model instanceof Event) {
                    Event::findOrFail($attributes['id'])->delete();
                } elseif ($model instanceof News) {
                    News::findOrFail($attributes['id'])->delete();
                }
            } catch (Throwable $e) {
                var_dump($e->getMessage());
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [];
    }
}
