<?php

namespace App\Services\OtpService;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class OtpBaseApi implements IOtpBaseApi
{
    public function get(string $url = '', array $options = [], array $query = []): PromiseInterface|Response
    {
        return Http::withHeaders([
            'Content-Type' => '',
        ])->withOptions([
            'verify' => false,
        ] + $options)->get(config('watzap.send_message_url').$url, [
            'api_key' => config('watzap.api_key'),
            'number_key' => config('watzap.number_key'),
        ] + $query);
    }

    public function post(string $url = '', array $options = [], array $body = []): PromiseInterface|Response
    {
        $accessKey = config('simrs.access_key');

        return Http::withHeaders([
            'Content-Type' => '',
        ])->withOptions([
            'verify' => false,
        ] + $options)->post(config('simrs.base_url').$url, [
            'api_key' => config('watzap.api_key'),
            'number_key' => config('watzap.number_key'),
        ] + $body);
    }
}
