<?php

namespace App\Services\SimrsService\DoctorService;

use App\Dto\SimrsDto\DoctorDataDto;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Http;

class DoctorService implements IDoctorService
{
    public function getDoctors(string $doctorId): DoctorDataDto
    {
        $accessKey = config("simrs.access_key");

        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/MobileWS2.asmx/ParamedicGetList", [
            "AccessKey" => $accessKey,
            "ParamedicID" => $doctorId,
            "ParamedicName" => "",
            "SmfID" => ""
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Error while connecting to SIMRS", 500);
        }

        $data = $response->json();

        return DoctorDataDto::from($data);
    }
}
