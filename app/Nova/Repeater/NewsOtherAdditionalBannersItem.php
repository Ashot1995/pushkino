<?php

namespace App\Nova\Repeater;

use App\Services\Nova\RepeaterFixID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class NewsOtherAdditionalBannersItem extends Repeatable
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
            RepeaterFixID::generate(),
            Image::make(__('Десктоп версия'), 'desktopImage')->help('Размер изображения: ШхB 580х380 (px) Формат: .jpg, .jpeg, .png'),
            Image::make(__('Мобильная версия'), 'mobileImage')->help('Размер изображения: ШхB 660х380 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'alt'),
            Textarea::make(__('Описание'), 'description'),
            Text::make(__('Текст кнопки'), 'buttonText'),
            Text::make(__('Ссылка'), 'buttonLink')->help('Пример формата ссылки: /news-and-events/event/{id мероприятия}/'),
        ];
    }

    public static function label()
    {
        return '';
    }
}
