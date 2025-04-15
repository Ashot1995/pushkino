<?php

namespace App\Nova;

use App\Nova\Repeater\StockGalleryItem;
use App\Nova\Repeater\StockLinksToLandingItem;
use App\Nova\Repeater\StockOtherAdditionalBannersItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Repeater;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mostafaznv\NovaCkEditor\CkEditor;
use Outl1ne\MultiselectField\Multiselect;

class Stock extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Stock>
     */
    public static $model = \App\Models\Stock::class;

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
        return __('Акции');
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

            Boolean::make(__('Публикация'), 'is_active')->default(true),

            Text::make(__('Заголовок'), 'heading'),
            Date::make(__('Начало'), 'start_date'),
            Date::make(__('Конец'), 'end_date'),
            CkEditor::make(__('Описание'), 'description')->hideFromIndex(),
            Text::make(__('Владелец акции'), 'storeName')->hideFromIndex(),

            Heading::make(__('Главный баннер')),

            Image::make(__('Десктоп версия'), 'mainBannerDesktop')->hideFromIndex()->help('Размер изображения: ШхB 710х365 (px) Формат: .jpg, .jpeg, .png'),
            Image::make(__('Мобильная версия'), 'mainBannerMobile')->hideFromIndex()->help('Размер изображения: ШхB 660х260 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'mainBannerAlt')->hideFromIndex(),

            Heading::make(__('Полноэкранный баннер')),

            Image::make(__('Десктоп версия'), 'additionalBannerDesktop')->hideFromIndex()->help('Размер изображения: ШхB 1180х365 (px) Формат: .jpg, .jpeg, .png'),
            Image::make(__('Мобильная версия'), 'additionalBannerMobile')->hideFromIndex()->help('Размер изображения: ШхB 660х260 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'additionalBannerAlt')->hideFromIndex(),

            Heading::make('')->onlyOnForms(),

            Repeater::make(__('Список дополнительных баннеров'), 'otherAdditionalBanners')->repeatables([
                StockOtherAdditionalBannersItem::make(),
            ])->asJson()->hideFromIndex(),

            Repeater::make(__('Ссылки на внутренние лендинги'), 'linksToLanding')->repeatables([
                StockLinksToLandingItem::make(),
            ])->asJson()->hideFromIndex(),

            Repeater::make(__('Галерея'), 'gallery')->repeatables([
                StockGalleryItem::make(),
            ])->asJson()->hideFromIndex(),

            Multiselect::make(__('Партнеры акции'), 'tenants')
                ->belongsToMany(Tenant::class, false)->hideFromIndex(),

            Multiselect::make(__('Другие акции'), 'similar')
                ->belongsToMany($this::class, false)->hideFromIndex(),

            Heading::make(__('Для главной страницы')),

            Boolean::make(__('Показать на главной странице'), 'is_main_display')->default(true),
            Text::make(__('Заголовок'), 'ovalBannerText')->hideFromIndex(),
            Image::make(__('Изображение'), 'ovalBanner')->hideFromIndex(),
            Text::make(__('Alt-текст'), 'ovalBannerAlt')->hideFromIndex(),
        ];
    }

    public function authorizedToView(Request $request)
    {
        return false;
    }
}
