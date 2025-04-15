<?php

namespace App\Nova;

use App\Enums\TenantTypeEnum;
use App\Nova\Repeater\TenantGalleryItem;
use App\Nova\Repeater\TenantLinksItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Repeater;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mostafaznv\NovaCkEditor\CkEditor;
use Outl1ne\MultiselectField\Multiselect;

class TheaterElement extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Tenant>
     */
    public static $model = \App\Models\TheaterElement::class;

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
        return __('Афиша театра III Р.И.М.');
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
            Boolean::make(__('Активность'), 'active'),
            Number::make(__('Сортировка'),'sort'),
            Text::make(__('Заголовок'), 'heading'),
            Textarea::make(__('Описание'), 'subheading'),
            Image::make(__('Изображение'), 'image')->help('Размер изображения: ШхB 380х380 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'alt')->hideFromIndex(),
            DateTime::make(__('Дата'), 'date'),
            DateTime::make(__('Дата нач. активности'),'active_from'),
            DateTime::make(__('Дата оконч. активности'),'active_to'),
            Text::make(__('Ссылка'), 'link')->hideFromIndex()->help('Пример формата ссылки: https://{адрес внешней страницы}/'),
        ];
    }

    public function authorizedToView(Request $request)
    {
        return false;
    }
}
