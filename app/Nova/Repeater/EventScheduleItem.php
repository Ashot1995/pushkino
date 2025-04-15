<?php

namespace App\Nova\Repeater;

use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class EventScheduleItem extends Repeatable
{
    /**
     * Get the fields displayed by the repeatable.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Date::make(__('Начало'), 'start_date'),
            Date::make(__('Конец'), 'end_date')->nullable(),
            Text::make(__('Время начала'), 'time')->help('Пример формата: 00:00'),
            Textarea::make(__('Описание'), 'description'),
            Text::make(__('Цена билета'), 'cost')->help('Пример формата: 00 руб.'),
        ];
    }

    public static function label()
    {
        return '';
    }
}
