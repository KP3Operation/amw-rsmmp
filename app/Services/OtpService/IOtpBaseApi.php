<?php

namespace App\Services\OtpService;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;

interface IOtpBaseApi
{
    public function get(string $url = '', array $options = [], array $query = []): PromiseInterface|Response;

    public function post(string $url = '', array $options = [], array $body = []): PromiseInterface|Response;
}
