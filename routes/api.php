<?php

use App\Http\Controllers\TenantController;
use App\Http\Controllers\EventLastController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Настройки
Route::apiResource('/settings', \App\Http\Controllers\SettingController::class)->only(['index']);

// Баннеры
Route::apiResource('/banners', \App\Http\Controllers\BannerController::class)->only(['index']);

// Акции
Route::get('/stocks/main', [\App\Http\Controllers\StockController::class, 'mainIndex']);
Route::apiResource('/stocks', \App\Http\Controllers\StockController::class)->only(['index', 'show']);

// Категории магазинов
Route::get('/categories/main', [\App\Http\Controllers\CategoryController::class, 'mainIndex']);
Route::apiResource('/categories', \App\Http\Controllers\CategoryController::class)->only(['index', 'show']);

// Магазины
Route::apiResource('/tenants', \App\Http\Controllers\TenantController::class)->only(['index', 'show']);
Route::get('/tenants/{slug}', [TenantController::class, 'show'])->name('tenants.show');

// Карта
Route::get('/map', [\App\Http\Controllers\MapController::class, 'index']);

// Новости
Route::get('/news/main', [\App\Http\Controllers\NewsController::class, 'mainIndex']);
Route::apiResource('/news', \App\Http\Controllers\NewsController::class)->only(['index', 'show']);

// Мероприятия
Route::get('/events/main', [\App\Http\Controllers\EventController::class, 'mainIndex']);
Route::apiResource('/events', \App\Http\Controllers\EventController::class)->only(['index', 'show']);
Route::apiResource('/event-lasts', \App\Http\Controllers\EventLastController::class)->only(['index', 'show']);

//
Route::get('/news-events/main', [\App\Http\Controllers\NewsEventController::class, 'mainIndex']);
Route::get('/news-events', [\App\Http\Controllers\NewsEventController::class, 'index']);

// Ритейл-парк
Route::apiResource('/retail-park-elements', \App\Http\Controllers\RetailParkElementController::class)->only(['index']);

// Афиша театра
Route::apiResource('/theater-elements', \App\Http\Controllers\TheaterElementController::class)->only(['index']);

// Форма обратной связи
Route::apiResource('/feedback-forms', \App\Http\Controllers\FeedbackFormController::class)->only(['store']);

// Вакансии
Route::apiResource('/vacancies', \App\Http\Controllers\VacancyController::class)->only(['index']);

// Парк развлечений
Route::apiResource('/amusement-park', \App\Http\Controllers\AmusementParkController::class)->only(['index']);

// Кино
Route::apiResource('/movie-posters', \App\Http\Controllers\MoviePosterController::class)->only(['index']);

// Статичные страницы
Route::apiResource('/static-pages', \App\Http\Controllers\StaticPageController::class)->only(['show']);

// Поиск
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index']);


