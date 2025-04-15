<?php

namespace App\Http\Controllers;


use App\Models\TheaterElement;

class TheaterElementController extends Controller
{
    public function index()
    {
        $theaterElements = TheaterElement::where(function ($query) {
            $query->whereNull('active_to')
                ->orWhere('active_to', '>=', now());
        })
            ->where('active', true)
            ->orderBy('sort')
            ->select([
                'image', 'alt', 'heading', 'subheading', 'date',
                'active_from', 'active_to', 'active', 'sort', 'link'
            ])
            ->get();

        return response()->json([
            'date' => now()->translatedFormat('l, d F, H:i'),
            'playbillTheaterList' => $theaterElements->map(function ($theaterElement) {
                return [
                    'image' => $this->storageFullPath($theaterElement->image),
                    'alt' => $theaterElement->alt,
                    'heading' => $theaterElement->heading,
                    'subheading' => $theaterElement->subheading,
                    'date' => $theaterElement->date?->translatedFormat('d.m.Y H:i'),
                    'active_from' => $theaterElement->active_from?->translatedFormat('d.m.Y H:i'),
                    'active_to' => $theaterElement->active_to?->translatedFormat('d.m.Y H:i'),
                    'active' => $theaterElement->active,
                    'sort' => $theaterElement->sort,
                    'link' => $theaterElement->link,
                ];
            }),
        ]);
    }
}
