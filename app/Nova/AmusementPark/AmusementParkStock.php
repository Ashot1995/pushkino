<?php

namespace App\Nova\AmusementPark;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mostafaznv\NovaCkEditor\CkEditor;

class AmusementParkStock extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Stock>
     */
    public static $model = \App\Models\AmusementParkStock::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'heading';

    public static function searchableColumns()
    {
        return Schema::getColumnListing((new self::$model)->getTable());
    }

    public static function label()
    {
        return __('Генератор блеска в глазах');
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

            Text::make(__('Заголовок'), 'heading'),
            CkEditor::make(__('Описание'), 'subheading')->hideFromIndex(),
            Image::make(__('Изображение'), 'image')->hideFromIndex()->help('Размер изображения: ШхB 580х280 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'alt')->hideFromIndex(),
            Text::make(__('Ссылка'), 'link')->hideFromIndex()->help('Пример формата ссылки: https://{адрес внешней страницы}/'),
        ];
    }

    public function authorizedToView(Request $request)
    {
        return false;
    }
}
