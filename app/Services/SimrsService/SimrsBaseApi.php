<?php

namespace App\Services\SimrsService;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class SimrsBaseApi implements ISimrsBaseApi
{
    public function get(string $url, array $options = [], array $query = []): PromiseInterface|Response
    {
        $accessKey = config('simrs.access_key');

        return Http::withHeaders([
            'Content-Type' => '',
        ])->withOptions([
            'verify' => false,
        ] + $options)->get(config('simrs.base_url').$url, [
            'AccessKey' => $accessKey,
        ] + $query);
    }

    public function post(string $url, array $options = [], array $body = []): PromiseInterface|Response
    {
        $accessKey = config('simrs.access_key');

        return Http::withHeaders([
            'Content-Type' => '',
        ])->withOptions([
            'verify' => false,
        ] + $options)->post(config('simrs.base_url').$url, [
            'AccessKey' => $accessKey] + $body);
    }
}
