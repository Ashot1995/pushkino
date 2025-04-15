<?php

namespace App\Nova\Repeater;

use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class ContactsOtherContactsItem extends Repeatable
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
            Text::make(__('Тип контакта'), 'contactType'),
            Text::make(__('Номер телефона'), 'phoneNumber'),
            Text::make(__('Имя менеджера'), 'managerName'),
            Text::make(__('E-mail менеджера'), 'managerEmail'),
        ];
    }

    public static function label()
    {
        return '';
    }
}
