<?php

namespace App\Nova;

use App\Enums\TenantTypeEnum;
use App\Nova\Repeater\TenantGalleryItem;
use App\Nova\Repeater\TenantLinksItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Repeater;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mostafaznv\NovaCkEditor\CkEditor;
use Outl1ne\MultiselectField\Multiselect;

class Tenant extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Tenant>
     */
    public static $model = \App\Models\Tenant::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'storeName';

    public static function searchableColumns()
    {
        return Schema::getColumnListing((new self::$model)->getTable());
    }

    public static function label()
    {
        return __('Арендаторы');
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
            Number::make(__('Сортировка'),'sort'),
            Select::make(__('Категория'), 'type')->options(collect(TenantTypeEnum::cases())->pluck('value', 'value'))->default('Магазины'),
            Text::make(__('Название'), 'storeName'),
            Text::make(__('SLUG'), 'slug')->required(),
            Text::make(__('Литер'), 'liter'),
            Number::make(__('Этаж'), 'floor')->hideFromIndex(),
            Text::make(__('ID на карте'), 'idSpace')->hideFromIndex(),
            Multiselect::make(__('Список подкатегорий'), 'categories')
                ->belongsToMany(Category::class, false)->hideFromIndex(),
            Image::make(__('Логотип'), 'logo')->help('Размер изображения: ШхB 80х80 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Время работы'), 'workingTime')->hideFromIndex(),
            Text::make(__('Номер телефона'), 'phoneNumber')->hideFromIndex(),
            Repeater::make(__('Ссылки на социальные сети'), 'links')->repeatables([
                TenantLinksItem::make(),
            ])->hideFromIndex(),

            Heading::make(__('Главный баннер')),

            Image::make(__('Десктоп версия'), 'mainImageDesktop')->hideFromIndex()->help('Размер изображения: ШхB 710х365 (px) Формат: .jpg, .jpeg, .png'),
            Image::make(__('Мобильная версия'), 'mainImageMobile')->hideFromIndex()->help('Размер изображения: ШхB 660х260 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'mainImageAlt')->hideFromIndex(),

            Heading::make('')->onlyOnForms(),

            CkEditor::make(__('Описание'), 'description')->hideFromIndex(),
            Repeater::make(__('Галерея'), 'gallery')->repeatables([
                TenantGalleryItem::make(),
            ])->asJson()->hideFromIndex(),
            Boolean::make(__('Новинка'), 'new')->default(false),
            Select::make(__('Наличие акции'), 'has_stocks')
                ->options([
                    'auto' => 'Автоматически',
                    'yes' => 'Да',
                    'no' => 'Нет',
                ])
                ->default('auto')
                ->hideFromIndex(),
            Multiselect::make(__('Акции'), 'stocks')
                ->belongsToMany(Stock::class, false)->hideFromIndex(),
            Multiselect::make(__('Похожие магазины'), 'similar')
                ->belongsToMany($this::class, false)->hideFromIndex(),
        ];
    }

    public function authorizedToView(Request $request)
    {
        return false;
    }
}
