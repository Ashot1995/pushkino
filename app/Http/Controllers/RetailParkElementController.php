<?php

namespace App\Http\Controllers;


use App\Models\RetailParkElement;

class RetailParkElementController extends Controller
{
    public function index()
    {
        $retailParkElements = RetailParkElement::all();

        return response()->json([
            'retailParkList' => $retailParkElements->map(function (RetailParkElement $retailParkElement) {
                return [
                    'image' => $this->storageFullPath($retailParkElement->image),
                    'alt' => $retailParkElement->alt,
                    'link' => $retailParkElement->link,
                ];
            }),
        ]);
    }
}
