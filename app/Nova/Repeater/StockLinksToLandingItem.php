<?php

namespace App\Nova\Repeater;

use App\Services\Nova\RepeaterFixID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class StockLinksToLandingItem extends Repeatable
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
            Image::make(__('Изображение'), 'image'),
            Text::make(__('Alt-текст'), 'alt'),
            Text::make(__('Заголовок'), 'heading'),
            Text::make(__('Ссылка'), 'link'),
        ];
    }

    public static function label()
    {
        return '';
    }
}
