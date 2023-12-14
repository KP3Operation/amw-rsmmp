<?php

namespace App\Services\SimrsService;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;

interface ISimrsBaseApi
{
    public function get(string $url, array $options = [], array $query = []): PromiseInterface|Response;
    public function post(string $url, array $options = [], array $body = []): PromiseInterface|Response;
}
