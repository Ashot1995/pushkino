<?php

namespace App\Nova\Repeater;

use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class ContactsRentManagersItem extends Repeatable
{

    /**
     * Get the fields displayed by the repeatable.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Text::make(__('Номер телефона'), 'phone')->rules('required'),
            Text::make(__('Имя менеджера'), 'managerName')->rules('required'),
            Text::make(__('E-mail'), 'managerEmail')->rules('required', 'email'),
        ];
    }

    public static function label()
    {
        return '';
    }
}
