<?php

namespace App\Services\SimrsService;

use App\Exceptions\SimrsException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class SimrsBaseApi implements ISimrsBaseApi
{
    /**
     * @throws SimrsException
     */
    public function get(string $url, array $options = [], array $query = []): PromiseInterface|Response
    {
        try {
            $accessKey = config('simrs.access_key');

            return Http::timeout(120)->withHeaders([
                'Content-Type' => '',        
            ])->withOptions([
                    'verify' => false,
                ] + $options)->get(config('simrs.base_url').$url, [
                    'AccessKey' => $accessKey,
                ] + $query);
        }catch (\Exception $e) {
            //throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }
    }

    /**
     * @throws SimrsException
     */
    public function post(string $url, array $options = [], array $body = []): PromiseInterface|Response
    {
        try {
            $accessKey = config('simrs.access_key');
            return Http::timeout(120)->withHeaders([
                'Content-Type' => '',
            ])->withOptions([
                    'verify' => false,
                ] + $options)->post(config('simrs.base_url').$url, [
                    'AccessKey' => $accessKey] + $body);

        }catch (\Exception $e) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }
    }
}
