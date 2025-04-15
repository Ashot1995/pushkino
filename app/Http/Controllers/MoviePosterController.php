<?php

namespace App\Http\Controllers;

use App\Models\MoviePoster;

class MoviePosterController extends Controller
{
    public function index()
    {
        $now = now();
        $moviePosters = MoviePoster::all();

        return response()->json([
            'date' => $now->translatedFormat('l, d F, H:i'),
            'takeIt' => $moviePosters->map(function (MoviePoster $moviePoster) {
                return [
                    'image' => $this->storageFullPath($moviePoster->image),
                    'alt' => $moviePoster->alt,
                    'heading' => $moviePoster->heading,
                    'description' => $moviePoster->description,
                    'link' => $moviePoster->link,
                ];
            }),
        ]);
    }
}
