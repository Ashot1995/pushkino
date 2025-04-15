<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\News;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class NewsEventController extends Controller
{
    public function mainIndex()
    {
        $news = News::mainDisplay()->latest('published_date')->get();
        $events = Event::mainDisplay()->latest('start_date')->get();
        $mixed = collect($news)->merge($events)->sortByDesc(function (Model $model) {
            return $model->published_date ?? $model->start_date;
        });

        return response()->json($mixed->map(function (News|Event $model) {
            return $this->response($model);
        })->take(18)->values());
    }

    public function index(Request $request)
    {
        $news = News::latest('published_date')->get();
        $events = Event::latest('start_date')->get();
        $mixed = collect($news)->merge($events)->sortByDesc(function (Model $model) {
            return $model->published_date ?? $model->start_date;
        });

        return response()->json($mixed->map(function (News|Event $model) {
            return $this->response($model);
        })->paginate(request('perPare', 9)));
    }

    public function response(News|Event $model): array
    {
        return [
            'heading' => $model->heading,
            'image' => $this->storageFullPath($model->mainImageDesktop),
            'alt' => $model->mainImageAlt,
            'text' => $model->description,
            'link' => $model->getFrontUrl(),
            'date' => $model->published_date ?? $model->start_date,
        ];
    }
}
