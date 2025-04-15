<?php

namespace App\Nova\Repeater;

use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class TenantLinksItem extends Repeatable
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
            Text::make(__('Ссылка на сайт'), 'website')->help('Пример формата ссылки: https://{адрес внешней страницы}/'),
            Text::make(__('Ссылка на VK'), 'vk')->help('Пример формата ссылки: https://{адрес внешней страницы}/'),
            Text::make(__('Ссылка на Telegram'), 'tg')->help('Пример формата ссылки: https://{адрес внешней страницы}/'),
        ];
    }

    public static function label()
    {
        return '';
    }
}
