<?php

namespace App\Nova;

use App\Nova\Actions\MoveToEventLast;
use App\Nova\Repeater\NewsGalleryItem;
use App\Nova\Repeater\NewsOtherAdditionalBannersItem;
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

class News extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Stock>
     */
    public static $model = \App\Models\News::class;

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
        return __('Новости');
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
            Date::make(__('Дата публикации'), 'published_date'),
            Boolean::make(__('Показать на главной странице'), 'is_main_display')->default(true),
            CkEditor::make(__('Описание'), 'description')->hideFromIndex(),

            Heading::make(__('Главный баннер')),

            Image::make(__('Десктоп версия'), 'mainImageDesktop')->hideFromIndex()->help('Размер изображения: ШхB 710х365 (px) Формат: .jpg, .jpeg, .png'),
            Image::make(__('Мобильная версия'), 'mainImageMobile')->hideFromIndex()->help('Размер изображения: ШхB 660х260 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'mainImageAlt')->hideFromIndex(),

            Multiselect::make(__('Партнеры'), 'tenants')
                ->belongsToMany(Tenant::class, false)->hideFromIndex(),
            Repeater::make(__('Список дополнительных баннеров'), 'otherAdditionalBanners')->repeatables([
                NewsOtherAdditionalBannersItem::make(),
            ])->asJson()->hideFromIndex(),
            Repeater::make(__('Галерея'), 'gallery')->repeatables([
                NewsGalleryItem::make(),
            ])->asJson()->hideFromIndex(),
            Multiselect::make(__('Другие новости'), 'similar')
                ->belongsToMany($this::class, false)->hideFromIndex(),
        ];
    }

    public function authorizedToView(Request $request)
    {
        return false;
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [
            (new MoveToEventLast())
                ->showInline()
        ];
    }
}
