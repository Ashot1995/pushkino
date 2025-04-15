<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class TheaterService
{
    private PendingRequest $http;

    public function __construct()
    {
        $this->http = Http::baseUrl(config('theater.base_url'))
            ->withBasicAuth(config('theater.username'), config('theater.password'));
    }

    public function events()
    {
        return collect($this->http->get('events')->json());
    }
}
