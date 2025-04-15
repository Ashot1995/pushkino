<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Alexwenzel\DependencyContainer\HasDependencies;
use Alexwenzel\DependencyContainer\DependencyContainer;

class Banner extends Resource
{
    use HasDependencies;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Banner>
     */
    public static $model = \App\Models\Banner::class;

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
        return __('Карусель баннеров');
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

            Select::make('Шаблон', 'template')->options([
                'two oval pictures' => 'Два овальных изображения',
                'one square picture' => 'Баннер на половину экрана',
                'two round pictures' => 'Два круглых изображения',
                'one full picture' => 'Баннер на весь экран',
            ])->required()->rules(['template' => 'required']),

            Text::make(__('Заголовок'),'heading')->onlyOnIndex(),

            DependencyContainer::make([
                ...$this->detail(),
                ...$this->firstOvalImage(),
                ...$this->secondOvalImage(),
            ])->dependsOn('template', 'two oval pictures'),

            DependencyContainer::make([
                ...$this->detail(),
                ...$this->firstSquareImage(),
            ])->dependsOn('template', 'one square picture'),

            DependencyContainer::make([
                ...$this->detail(),
                ...$this->firstRoundImage(),
                ...$this->secondRoundImage(),
            ])->dependsOn('template', 'two round pictures'),

            DependencyContainer::make([
                ...$this->detail(),
                ...$this->firstFullImage(),
            ])->dependsOn('template', 'one full picture'),
        ];
    }

    // Oval

    private function firstOvalImage()
    {
        return [
            Heading::make('Изображение слева (основное)'),

            Image::make(__('Десктоп версия'), 'firstImageDesktop')->deletable(false)->help('Размер изображения: ШхB 380х640 (px) Формат: .jpg, .jpeg, .png'),
            Image::make(__('Мобильная версия'), 'firstImageMobile')->deletable(false)->help('Размер изображения: ШхB 390х650 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'firstImageAlt'),
        ];
    }

    private function secondOvalImage()
    {
        return [
            Heading::make('Изображение справа'),

            Image::make(__('Десктоп версия'), 'secondImageDesktop')->deletable(false)->help('Размер изображения: ШхB 380х640 (px) Формат: .jpg, .jpeg, .png'),
            Image::make(__('Мобильная версия'), 'secondImageMobile')->deletable(false)->help('Размер изображения: ШхB 390х650 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'secondImageAlt'),
        ];
    }

    // Square

    private function firstSquareImage()
    {
        return [
            Heading::make('Изображение слева (основное)'),

            Image::make(__('Десктоп версия'), 'firstImageDesktop')->deletable(false)->help('Размер изображения: ШхB 780х640 (px) Формат: .jpg, .jpeg, .png'),
            Image::make(__('Мобильная версия'), 'firstImageMobile')->deletable(false)->help('Размер изображения: ШхB 390х650 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'firstImageAlt'),
        ];
    }

    // Round

    private function firstRoundImage()
    {
        return [
            Heading::make('Изображение слева (основное)'),

            Image::make(__('Десктоп версия'), 'firstImageDesktop')->deletable(false)->help('Размер изображения: ШхB 600х600 (px) Формат: .jpg, .jpeg, .png'),
            Image::make(__('Мобильная версия'), 'firstImageMobile')->deletable(false)->help('Размер изображения: ШхB 390х650 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'firstImageAlt'),
        ];
    }

    private function secondRoundImage()
    {
        return [
            Heading::make('Изображение справа'),

            Image::make(__('Десктоп версия'), 'secondImageDesktop')->deletable(false)->help('Размер изображения: ШхB 200х200 (px) Формат: .jpg, .jpeg, .png'),
            Image::make(__('Мобильная версия'), 'secondImageMobile')->deletable(false)->help('Размер изображения: ШхB 100х100 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'secondImageAlt'),
        ];
    }

    // Full

    private function firstFullImage()
    {
        return [
            Heading::make('Изображение слева (основное)'),

            Image::make(__('Десктоп версия'), 'firstImageDesktop')->deletable(false)->help('Размер изображения: ШхB 1180х450 (px) Формат: .jpg, .jpeg, .png'),
            Image::make(__('Мобильная версия'), 'firstImageMobile')->deletable(false)->help('Размер изображения: ШхB 390х650 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'firstImageAlt'),
        ];
    }

    //

    private function detail()
    {
        $fields = [
            Text::make(__('Заголовок'), 'heading'),
            Textarea::make(__('Описание'), 'description'),
            Text::make(__('Цвет текста описания'), 'colorText')->help('Пример формата: #FFFFFF'),
            Text::make(__('Цвет фона баннера'), 'backgroundColor')->help('Пример формата: #FFFFFF'),
            Text::make(__('Текст кнопки'), 'buttonText'),
            Text::make(__('Цвет текста кнопки'), 'colorButtonText')->help('Пример формата: #FFFFFF'),
            Text::make(__('Цвет фона кнопки'), 'backgroundColorButton')->help('Пример формата: #FFFFFF'),
            Text::make(__('Ссылка'), 'link'),
        ];

        return [
            Heading::make('Информация страницы'),

            ...$fields,
        ];
    }

    public function authorizedToView(Request $request)
    {
        return false;
    }
}
