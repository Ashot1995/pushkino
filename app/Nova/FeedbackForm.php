<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\File;


class FeedbackForm extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Stock>
     */
    public static $model = \App\Models\FeedbackForm::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'full_name';

    public static function searchableColumns()
    {
        return Schema::getColumnListing((new self::$model)->getTable());
    }

    public static function label()
    {
        return __('Заявки от арендаторов');
    }

    public static function createButtonLabel()
    {
        return __('Создать');
    }

    public static function updateButtonLabel()
    {
        return __('Обновить');
    }

    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/'.static::uriKey();
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/'.static::uriKey();
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make(__('Ф.И.О'), 'full_name'),
            Text::make(__('E-mail'), 'email'),
            Text::make(__('Номер телефона'), 'phone'),
            Textarea::make(__('Сообщение'), 'message')->hideFromIndex(),
            File::make(__('Файл'), 'file')->hideFromIndex(),
        ];
    }

    public function authorizedToView(Request $request)
    {
        return false;
    }
}
