<?php

namespace App\Providers;

use App\Nova\AmusementPark\AmusementParkEvent;
use App\Nova\AmusementPark\AmusementParkStock;
use App\Nova\Banner;
use App\Nova\Category;
use App\Nova\Event;
use App\Nova\EventLast;
use App\Nova\FeedbackForm;
use App\Nova\MoviePoster;
use App\Nova\News;
use App\Nova\RetailParkElement;
use App\Nova\Setting;
use App\Nova\StaticPage;
use App\Nova\Stock;
use App\Nova\Tenant;
use App\Nova\TheaterElement;
use App\Nova\User;
use App\Nova\Vacancy;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Menu\MenuGroup;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::mainMenu(function ($request) {
            return [
                MenuSection::make(__('Главная страница'), [
                    MenuItem::resource(Banner::class),
                    MenuItem::resource(TheaterElement::class),
                    MenuGroup::make(__('Парк развлечений Флик Фляк'), [
                        MenuItem::resource(AmusementParkStock::class),
                        MenuItem::resource(AmusementParkEvent::class),
                    ])->collapsable(),
                    MenuItem::resource(MoviePoster::class),
                    MenuItem::resource(RetailParkElement::class),
                ])->collapsable(),
                MenuSection::make(__('Каталог'), [
                    MenuItem::resource(Category::class),
                    MenuItem::resource(Tenant::class),
                ])->collapsable(),
                MenuSection::make(__('Новости и мероприятия'), [
                    MenuItem::resource(News::class),
                    MenuItem::resource(Event::class),
                    MenuItem::resource(EventLast::class),
                ])->collapsable(),
                MenuItem::resource(Stock::class),
                MenuSection::make(__('Информация'), [
                    MenuItem::resource(StaticPage::class),
                    MenuItem::resource(Vacancy::class),
                ])->collapsable(),
                MenuItem::resource(Setting::class),
                MenuItem::resource(FeedbackForm::class),
                MenuItem::resource(User::class),
            ];
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return true;
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
