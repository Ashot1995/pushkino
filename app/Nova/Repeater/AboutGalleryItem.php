<?php

namespace App\Nova\Repeater;

use App\Services\Nova\RepeaterFixID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class AboutGalleryItem extends Repeatable
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
            Image::make(__('Десктоп версия'), 'desktopImage')->deletable(false)->help('Размер изображения: ШхB 580х580 (px) Формат: .jpg, .jpeg, .png'),
            Image::make(__('Мобильная версия'), 'mobileImage')->deletable(false)->help('Размер изображения: ШхB 320х440 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'alt'),
        ];
    }

    public static function label()
    {
        return '';
    }
}
