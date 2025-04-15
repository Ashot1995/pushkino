<?php

namespace App\Nova\Repeater;

use App\Services\Nova\RepeaterFixID;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class TenantsContactsItem extends Repeatable
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
            Text::make(__('Заголовок'), 'heading'),
            Textarea::make(__('Описание'), 'description'),
            Text::make(__('Номер телефона'), 'phoneNumber'),
            Text::make(__('Имя менеджера'), 'managerName'),
            Text::make(__('E-mail менеджера'), 'managerEmail'),
            File::make(__('Презентация'), 'presentation'),
        ];
    }

    public static function label()
    {
        return '';
    }
}
