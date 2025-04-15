<?php

namespace App\Http\Controllers;


use App\Enums\TenantTypeEnum;
use App\Models\Event;
use App\Models\News;
use App\Models\StaticPage;
use App\Models\Tenant;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => 'required|string',
        ]);

        return response()->json([
            'commercial' => $this->search(Tenant::getModel())->get()->map(function (Tenant $tenant) {
                return [
                    'id' => $tenant->getKey(),
                    'idSpace' => $tenant->idSpace,
                    'logo' => $this->storageFullPath($tenant->logo),
                    'heading' => $tenant->storeName,
                    'category' => $tenant->type,
                    'subCategory' => $tenant->categories,
                    'floor' => $tenant->floor,
                    'link' => $tenant->getFrontUrl(),
                ];
            }),
            'news' => $this->search(News::getModel())->get()->map(function (News $model) {
                return [
                    'id' => $model->getKey(),
                    'image' => $this->storageFullPath($model->mainImageDesktop),
                    'heading' => $model->heading,
                    'description' => $model->description,
                    'publishedDate' => $model->published_date,
                    'eventDate' => $model->start_date,
                    'link' => $model->getFrontUrl(),
                ];
            }),
            'events' => $this->search(Event::getModel())->get()->map(function (Event $model) {
                return [
                    'id' => $model->getKey(),
                    'image' => $this->storageFullPath($model->mainImageDesktop),
                    'heading' => $model->heading,
                    'description' => $model->description,
                    'publishedDate' => $model->published_date,
                    'eventDate' => $model->start_date,
                    'link' => $model->getFrontUrl(),
                ];
            }),
            'stocks' => $this->search(Stock::getModel())->active()->get()->map(function (Stock $model) {
                return [
                    'id' => $model->getKey(),
                    'image' => $this->storageFullPath($model->mainImageDesktop),
                    'heading' => $model->heading,
                    'description' => $model->description,
                    'publishedDate' => $model->published_date,
                    'eventDate' => $model->start_date,
                    'link' => $model->getFrontUrl(),
                ];
            }),
            'info' => $this->search(StaticPage::getModel())->get()->map(function (StaticPage $staticPage) {
                return [
                    'heading' => Arr::get($staticPage->fields, 'heading'),
                    'link' => $staticPage->getFrontUrl(),
                    'api_link' => route('static-pages.show', $staticPage, false),
                ];
            }),
        ]);
    }

    public function search(Model $model)
    {
        $q = $model;
        $requestQ = request('q');

        if ($model instanceof StaticPage) {
            if (mb_stripos('Контакты', $requestQ) !== false) {
                $requestQ = 'contacts';
            } else if (mb_stripos('О ТРЦ', $requestQ) !== false) {
                $requestQ = 'about';
            } else if (mb_stripos('Арендаторам', $requestQ) !== false) {
                $requestQ = 'tenants';
            } else if (mb_stripos('Правила ТРЦ', $requestQ) !== false) {
                $requestQ = 'rules';
            }
        }

        foreach (Schema::getColumnListing($model->getTable()) as $column) {
            $q = $q->orWhere($column, 'like', '%' . $requestQ . '%');
        }

        if ($model instanceof Tenant) {
            switch ($requestQ) {
                case 'shops':
                    $type = TenantTypeEnum::Shop;
                    break;
                case 'cafes':
                    $type = TenantTypeEnum::Restaurant;
                    break;
                case 'recreation':
                    $type = TenantTypeEnum::Entertainment;
                    break;
                case 'services':
                    $type = TenantTypeEnum::Service;
                    break;
                default:
                    $type = $requestQ;
            }

            $q->orWhere('type', $type);

            $q->orWhereHas('categories', function ($query) use ($requestQ) {
                $query->whereIn('id', explode(',', $requestQ))
                    ->orWhere('category', 'like', '%' . $requestQ . '%');
            });
        }

        return $q;
    }
}
