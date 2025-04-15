<?php

namespace App\Nova;

use App\Nova\Repeater\AboutAdvantagesItem;
use App\Nova\Repeater\AboutDescriptionBannersItem;
use App\Nova\Repeater\AboutGalleryItem;
use App\Nova\Repeater\AboutTextBannersItem;
use App\Nova\Repeater\ContactsAdminPhonesItem;
use App\Nova\Repeater\ContactsOtherContactsItem;
use App\Nova\Repeater\TenantsAdvantagesItem;
use App\Nova\Repeater\TenantsContactsItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Repeater;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mostafaznv\NovaCkEditor\CkEditor;

class StaticPage extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Stock>
     */
    public static $model = \App\Models\StaticPage::class;

    public static $layouts = [
        'about' => 'О ТРЦ',
        'rules' => 'Правила ТРЦ',
        'contacts' => 'Контакты',
        'tenants' => 'Арендаторам',
    ];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'slug';

    public static function searchableColumns()
    {
        return Schema::getColumnListing((new self::$model)->getTable());
    }

    public static function label()
    {
        return __('О нас');
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

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
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

            Text::make(__('Страница'), 'textLayout')->onlyOnIndex(),
            Text::make(__('Страница'), 'textLayout')->onlyOnDetail(),
            Select::make(__('Страница'), 'layout')
                ->options($this::$layouts)
                ->readonly()
                ->onlyOnForms()
                ->required(),
            Text::make(__('SLUG'), 'slug')
                ->readonly()
                ->required(),

            ...$this->resolve($this->model()->layout),
        ];
    }

    private function resolve($layout)
    {
        if (method_exists($this, $layout)) {
            return $this->{$layout}();
        }

        return [];
    }

    private function about()
    {
        return [
            Heading::make(__('SEO данные'))->hideFromIndex(),

            Text::make(__('Title'), 'fields->metaTitle')->hideFromIndex(),
            Text::make(__('Description'), 'fields->metaDescription')->hideFromIndex(),
            Text::make(__('Keywords'), 'fields->metaKeywords')->hideFromIndex(),

            Heading::make('Информация страницы'),

            Text::make(__('Заголовок'), 'fields->heading')->hideFromIndex(),
            CkEditor::make(__('Описание'), 'fields->description')->hideFromIndex(),
            File::make(__('Презентация'), 'fields->presentation')->hideFromIndex(),

            Heading::make(__('Главный баннер')),

            Image::make(__('Десктоп версия'), 'fields->mainBannerDesktop')->hideFromIndex()->help('Размер изображения: ШхB 1180х365 (px) Формат: .jpg, .jpeg, .png'),
            Image::make(__('Мобильная версия'), 'fields->mainBannerMobile')->hideFromIndex()->help('Размер изображения: ШхB 660х260 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'fields->mainBannerAlt')->hideFromIndex(),

            Heading::make(__('Полноэкранный баннер')),

            Image::make(__('Десктоп версия'), 'fields->additionalBannerDesktop')->hideFromIndex()->help('Размер изображения: ШхB 1180х365 (px) Формат: .jpg, .jpeg, .png'),
            Image::make(__('Мобильная версия'), 'fields->additionalBannerMobile')->hideFromIndex()->help('Размер изображения: ШхB 660х260 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'fields->additionalBannerAlt')->hideFromIndex(),

            Repeater::make(__('Список дополнительных баннеров'), 'aboutTextBanners')->repeatables([
                AboutTextBannersItem::make(),
            ])->asJson()->hideFromIndex(),

            Repeater::make(__('Список преимуществ'), 'aboutAdvantages')->repeatables([
                AboutAdvantagesItem::make(),
            ])->asJson()->hideFromIndex(),

            Text::make(__('Заголовок слайдера'), 'fields->sliderWithDescriptionHeading')->hideFromIndex(),
            Textarea::make(__('Описание слайдера'), 'fields->sliderWithDescriptionDescription')->hideFromIndex(),

            Repeater::make(__('Список изображений для слайдера'), 'aboutGallery')->repeatables([
                AboutGalleryItem::make(),
            ])->asJson()->hideFromIndex(),
        ];
    }

    private function rules()
    {
        return [
            Heading::make(__('SEO данные'))->hideFromIndex(),

            Text::make(__('Title'), 'fields->metaTitle')->hideFromIndex(),
            Text::make(__('Description'), 'fields->metaDescription')->hideFromIndex(),
            Text::make(__('Keywords'), 'fields->metaKeywords')->hideFromIndex(),

            Heading::make('Информация страницы'),

            Text::make(__('Заголовок'), 'fields->heading')->hideFromIndex(),

            Image::make(__('Десктоп версия'), 'fields->desktopImage')->hideFromIndex()->help('Размер изображения: ШхB 710х380 (px) Формат: .jpg, .jpeg, .png'),
            Image::make(__('Мобильная версия'), 'fields->mobileImage')->hideFromIndex()->help('Размер изображения: ШхB 660х260 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'fields->alt')->hideFromIndex(),
            Text::make(__('Номер редакции'), 'fields->version')->hideFromIndex(),
            Date::make(__('Дата'), 'fields->date')->hideFromIndex(),
            File::make(__('Документ'), 'fields->document')->hideFromIndex(),
        ];
    }

    private function contacts()
    {
        return [
            Heading::make(__('SEO данные'))->hideFromIndex(),

            Text::make(__('Title'), 'fields->metaTitle')->hideFromIndex(),
            Text::make(__('Description'), 'fields->metaDescription')->hideFromIndex(),
            Text::make(__('Keywords'), 'fields->metaKeywords')->hideFromIndex(),

            Heading::make('Информация страницы'),

            Text::make(__('Заголовок'), 'fields->heading')->hideFromIndex(),

            Heading::make(__('Администрация'))->hideFromIndex(),
            Repeater::make(__('Телефоны'), 'contactsAdminPhone')->repeatables([
                ContactsAdminPhonesItem::make(),
            ])->asJson()->hideFromIndex(),
            Text::make(__('E-mail'), 'fields->adminEmail')->hideFromIndex(),

            Heading::make(__('Менеджер по аренде помещений'))->hideFromIndex(),
            Text::make(__('Номер телефона'), 'fields->rentManagerPhone')->hideFromIndex(),
            Text::make(__('Имя'), 'fields->rentManagerName')->hideFromIndex(),
            Text::make(__('E-mail'), 'fields->rentManagerEmail')->hideFromIndex(),

            Heading::make(__('Менеджер по рекламе'))->hideFromIndex(),
            Text::make(__('Номер телефона'), 'fields->adManagerPhone')->hideFromIndex(),
            Text::make(__('Имя'), 'fields->adManagerName')->hideFromIndex(),
            Text::make(__('E-mail'), 'fields->adManagerEmail')->hideFromIndex(),

            Repeater::make(__('Список контактов по типам'), 'contactsOtherContactsList')->repeatables([
                ContactsOtherContactsItem::make(),
            ])->asJson()->hideFromIndex(),
        ];
    }

    private function tenants()
    {
        return [
            Heading::make(__('SEO данные'))->hideFromIndex(),

            Text::make(__('Title'), 'fields->metaTitle')->hideFromIndex(),
            Text::make(__('Description'), 'fields->metaDescription')->hideFromIndex(),
            Text::make(__('Keywords'), 'fields->metaKeywords')->hideFromIndex(),

            Heading::make('Информация страницы'),

            Text::make(__('Заголовок'), 'fields->heading')->hideFromIndex(),

            Repeater::make(__('Список преимуществ ТРЦ'), 'tenantsAdvantages')->repeatables([
                TenantsAdvantagesItem::make(),
            ])->asJson()->hideFromIndex(),

            Heading::make(__('Основной баннер')),

            Image::make(__('Десктоп версия'), 'fields->mainBannerDesktop')->hideFromIndex()->help('Размер изображения: ШхB 580 х365 (px) Формат: .jpg, .jpeg, .png'),
            Image::make(__('Мобильная версия'), 'fields->mainBannerMobile')->hideFromIndex()->help('Размер изображения: ШхB 660х260 (px) Формат: .jpg, .jpeg, .png'),
            Text::make(__('Alt-текст'), 'fields->mainBannerAlt')->hideFromIndex(),
            Textarea::make(__('Описание'), 'fields->mainBannerDescription')->hideFromIndex(),
            Text::make(__('Текст кнопки'), 'fields->mainBannerButtonText')->hideFromIndex(),
            Text::make(__('Ссылка'), 'fields->mainBannerButtonLink')->hideFromIndex(),

            Repeater::make(__('Контакты'), 'tenantsContacts')->repeatables([
                TenantsContactsItem::make(),
            ])->asJson()->hideFromIndex(),
        ];
    }

    public function authorizedToView(Request $request)
    {
        return false;
    }
}
