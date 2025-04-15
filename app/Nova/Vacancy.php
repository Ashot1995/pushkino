<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Alexwenzel\DependencyContainer\HasDependencies;
use Alexwenzel\DependencyContainer\DependencyContainer;
use Mostafaznv\NovaCkEditor\CkEditor;

class Vacancy extends Resource
{
    use HasDependencies;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Banner>
     */
    public static $model = \App\Models\Vacancy::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    public static function searchableColumns()
    {
        return Schema::getColumnListing((new self::$model)->getTable());
    }

    public static function label()
    {
        return __('Вакансии');
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

            Select::make(__('Тип'), 'type')->options([
                'ТРЦ Пушкино Парк' => 'ТРЦ Пушкино Парк',
                'Арендаторы' => 'Арендаторы',
                'ФЛИК ФЛЯК' => 'ФЛИК ФЛЯК',
                'ТЕАТР III Р.И.М.' => 'ТЕАТР III Р.И.М.',
                'АНДЕРСОН' => 'АНДЕРСОН',
                'Пеликан МЕБЕЛЬНЫЙ ЦЕНТР' => 'Пеликан МЕБЕЛЬНЫЙ ЦЕНТР',
                'Chisto Kristo' => 'Chisto Kristo',
            ])->default('ТРЦ Пушкино Парк')->required(),
            Image::make(__('Логотип'), 'logo')->help('Размер изображения: ШхB 200х100 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'alt')->hideFromIndex(),
            Text::make(__('Работодатель'), 'employerName'),
            Date::make(__('Дата публикации'), 'date'),
            Text::make(__('Должность'), 'position'),
            CkEditor::make(__('Обязанности'), 'duties')->hideFromIndex(),
            CkEditor::make(__('Требования'), 'requirements')->hideFromIndex(),
            CkEditor::make(__('Условия'), 'conditions')->hideFromIndex(),
            Text::make(__('Номер телефона'), 'phoneNumber')->hideFromIndex(),
            Text::make(__('E-mail'), 'email')->hideFromIndex(),
        ];
    }

    public function authorizedToView(Request $request)
    {
        return false;
    }
}
