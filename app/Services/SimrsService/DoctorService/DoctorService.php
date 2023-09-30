<?php

namespace App\Services\SimrsService\DoctorService;

use App\Dto\SimrsDto\Doctor\DoctorDataDto;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Http;
use App\Dto\SimrsDto\Doctor\DoctorAppointmentListDataDto;
use App\Dto\SimrsDto\Doctor\DoctorAppointmentListDetailDto;
use App\Dto\SimrsDto\Doctor\DoctorSummaryFeeDataDto;

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

    public function getSummaryFee(string $paramedicId, string $startDate, string $endDate): DoctorSummaryFeeDataDto
    {
        $accessKey = config("simrs.access_key");
        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/MobileWS.asmx/ParamedicFeeSummaryByParamedicIdTransDate", [
            "AccessKey" => $accessKey,
            "ParamedicID" => $paramedicId,
            "TransactionDateStart" => convert_date_to_req_param($startDate),
            "TransactionDateEnd" => convert_date_to_req_param($endDate)
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Error while connecting to SIMRS", 500);
        }

        $data = $response->json();

        return DoctorSummaryFeeDataDto::from($data);
    }
}
