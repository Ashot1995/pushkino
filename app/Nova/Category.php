<?php

namespace App\Nova;

use Alexwenzel\DependencyContainer\DependencyContainer;
use App\Enums\TenantTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Category extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Category>
     */
    public static $model = \App\Models\Category::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'category';

    public static function searchableColumns()
    {
        return Schema::getColumnListing((new self::$model)->getTable());
    }

    public static function label()
    {
        return __('Категории');
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
            Select::make(__('Тип'), 'type')->options(collect(TenantTypeEnum::cases())->pluck('value', 'value'))->default('Магазины'),
            Text::make(__('Категория'), 'category'),

            DependencyContainer::make([
                Boolean::make(__('Показать на главной странице'), 'is_main_display')->default(true),
                Image::make(__('Изображение'), 'image'),
                Text::make(__('Alt-текст'), 'imageAlt'),
            ])->dependsOn('type', TenantTypeEnum::Shop),

        ];
    }

    public function authorizedToView(Request $request)
    {
        return false;
    }
}
