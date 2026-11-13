<?php

namespace App\Services\SimrsService;

use App\Exceptions\SimrsException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SimrsBaseApi implements ISimrsBaseApi
{
    /**
     * Callers of these endpoints inspect the SIMRS envelope themselves and pass its
     * message on to the client, so the shared guard below must leave them alone.
     */
    private const ENDPOINTS_WITH_OWN_ERROR_HANDLING = [
        '/V1_1/AppointmentWS.asmx/AppointmentCreate',
        '/AppointmentWS.asmx/AppointmentGetListByMedicalNo',
        '/AppointmentWS.asmx/AppointmentGetOne',
        '/AppointmentWS.asmx/PatientGetOne',
    ];

    /**
     * @throws SimrsException
     */
    public function get(string $url, array $options = [], array $query = []): PromiseInterface|Response
    {
        try {
            $accessKey = config('simrs.access_key');

            $response = Http::timeout(120)->withHeaders([
                'Content-Type' => '',
            ])->withOptions([
                'verify' => false,
            ] + $options)->get(config('simrs.base_url').$url, [
                'AccessKey' => $accessKey,
            ] + $query);
        } catch (\Exception $e) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $this->failOnSimrsErrorEnvelope($response, $url);

        return $response;
    }

    /**
     * @throws SimrsException
     */
    public function post(string $url, array $options = [], array $body = []): PromiseInterface|Response
    {
        try {
            $accessKey = config('simrs.access_key');

            $response = Http::timeout(120)->withHeaders([
                'Content-Type' => '',
            ])->withOptions([
                'verify' => false,
            ] + $options)->post(config('simrs.base_url').$url, [
                'AccessKey' => $accessKey,
            ] + $body);
        } catch (\Exception $e) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $this->failOnSimrsErrorEnvelope($response, $url);

        return $response;
    }

    /**
     * SIMRS answers HTTP 200 even when its own query fails, reporting the failure
     * through the response envelope instead. Left unchecked, that error text
     * reaches the DTOs where a row array is expected and surfaces as an
     * unreadable "Unable to normalize items" exception.
     *
     * @throws SimrsException
     */
    private function failOnSimrsErrorEnvelope(Response $response, string $url): void
    {
        if (! $response->successful() || in_array($url, self::ENDPOINTS_WITH_OWN_ERROR_HANDLING, true)) {
            return;
        }

        $payload = $response->json();

        if (! is_array($payload) || ($payload['status'] ?? null) !== 'ERR') {
            return;
        }

        Log::warning('SIMRS returned an error envelope', [
            'endpoint' => $url,
            'errorCode' => $payload['errorCode'] ?? null,
            'detail' => is_string($payload['data'] ?? null) ? $payload['data'] : null,
        ]);

        throw new SimrsException('Gagal memuat data dari SIMRS, mohon coba beberapa saat lagi', 500);
    }
}
