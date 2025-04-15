<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Oneduo\NovaTimeField\Time;

class Setting extends Resource
{
    public static $defaultSort = 'order';

    public function __construct($resource = null)
    {
        parent::__construct($resource);

        $this->sync();
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if (static::$defaultSort && empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];
            return $query->orderBy(static::$defaultSort);
        }
        return $query;
    }

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Setting>
     */
    public static $model = \App\Models\Setting::class;

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
        return __('Хедер/Футер');
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
        $label = __('Значение');
        $attribute = 'value';

        switch ($this->model()->type) {
            case 'textarea':
                $value = Textarea::make($label, $attribute)->alwaysShow();
                break;
            case 'image':
                $value = Image::make($label, $attribute);
                break;
            case 'time':
                $value = Time::make($label, $attribute);
                break;
            default:
                $value = Text::make($label, $attribute);
        }

        return [
            Text::make(__('Параметр'), 'name')->readonly()->sortable(),
            $value->hideFromIndex(),
        ];
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    private function sync(): void
    {
        $settings = [
            [
                'key' => 'working_hours_trc_days',
                'name' => 'Дни работы ТРЦ',
            ],
            [
                'key' => 'working_hours_trc_hours',
                'name' => 'Время работы ТРЦ',
            ],
            [
                'key' => 'working_hours_cinema_days',
                'name' => 'Дни работы кинотеатра',
            ],
            [
                'key' => 'working_hours_cinema_hours',
                'name' => 'Время работы кинотеатра',
            ],
            [
                'key' => 'phone_number',
                'name' => 'Номер телефона',
            ],
            [
                'key' => 'logo',
                'name' => 'Логотип (белый)',
                'type' => 'image',
            ],
            [
                'key' => 'black_logo',
                'name' => 'Логотип (черный)',
                'type' => 'image',
            ],
            [
                'key' => 'address',
                'name' => 'Адрес',
                'type' => 'textarea',
            ],
            [
                'key' => 'sociallinks_telegram',
                'name' => 'Ссылка на Telegram',
                'help' => 'Пример формата ссылки: https://{адрес внешней страницы}/',
            ],
            [
                'key' => 'sociallinks_vkontakte',
                'name' => 'Ссылка на VK',
                'help' => 'Пример формата ссылки: https://{адрес внешней страницы}/',
            ],
        ];


        foreach ($settings as $settingData) {
            $model = new $this::$model();
            $setting = $model->firstWhere('key', $settingData['key']);

            if (is_null($setting)) {
                $setting = new $this::$model();
                $setting->key = $settingData['key'];
                $setting->name = $settingData['name'];
                $setting->type = $settingData['type'] ?? 'text';
                $setting->save();
            } else {
                $setting->name = $settingData['name'];
                $setting->type = $settingData['type'] ?? 'text';
                $setting->save();
            }
        }
    }

    public function authorizedToView(Request $request)
    {
        return false;
    }
}
