<?php

namespace App\Http\Controllers;

use App\Models\AmusementParkEvent;
use App\Models\AmusementParkStock;

class AmusementParkController extends Controller
{
    public function index()
    {
        $amusementParkEvents = AmusementParkEvent::all();
        $amusementParkStocks = AmusementParkStock::all();

        return response()->json([
            'eventsList' => $amusementParkEvents->map(function (AmusementParkEvent $amusementParkEvent) {
                return [
                    'image' => $this->storageFullPath($amusementParkEvent->image),
                    'alt' => $amusementParkEvent->alt,
                    'heading' => $amusementParkEvent->heading,
                    'link' => $amusementParkEvent->link,
                ];
            }),
            'stocksList' => $amusementParkStocks->map(function (AmusementParkStock $amusementParkStock) {
                return [
                    'image' => $this->storageFullPath($amusementParkStock->image),
                    'alt' => $amusementParkStock->alt,
                    'heading' => $amusementParkStock->heading,
                    'subheading' => $amusementParkStock->subheading,
                    'link' => $amusementParkStock->link,
                ];
            }),
        ]);
    }
}
