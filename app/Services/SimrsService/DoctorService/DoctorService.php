<?php

namespace App\Services\SimrsService\DoctorService;

use App\Dto\SimrsDto\Doctor\AppointmentDataDto;
use App\Dto\SimrsDto\Doctor\AppointmentDetailDataDto;
use App\Dto\SimrsDto\Doctor\DoctorDataDto;
use App\Dto\SimrsDto\Doctor\DoctorFeeByTrxDateDataDto;
use App\Dto\SimrsDto\Doctor\InpatientListDataDto;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Http;
use App\Dto\SimrsDto\Doctor\DoctorFeeByPaymentDateDataDto;
use App\Dto\SimrsDto\Doctor\DoctorSummaryFeeDataDto;
use App\Dto\SimrsDto\Doctor\PatientRegistrationCPPTDataDto;

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
            throw new HttpClientException("Failed connecting to SIMRS", 500);
        }

        $data = $response->json();

        return DoctorDataDto::from($data);
    }

    public function getOverviewSummaryFee(string $paramedicId, string $startDate, string $endDate): DoctorSummaryFeeDataDto
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
            throw new HttpClientException("Failed connecting to SIMRS", 500);
        }

        $data = $response->json();

        return DoctorSummaryFeeDataDto::from($data);
    }

    public function getFeeByTrxDate(string $paramedicId, string $startDate, string $endDate): DoctorFeeByTrxDateDataDto
    {
        $accessKey = config("simrs.access_key");
        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/MobileWS.asmx/ParamedicFeeByParamedicIDTransDate", [
            "AccessKey" => $accessKey,
            "ParamedicID" => $paramedicId,
            "TransactionDateStart" => convert_date_to_req_param($startDate),
            "TransactionDateEnd" => convert_date_to_req_param($endDate)
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Failed connecting to SIMRS", 500);
        }

        $data = $response->json();

        return DoctorFeeByTrxDateDataDto::from($data);
    }

    public function getFeeByPaymentDate(string $paramedicId, string $paymentDateStart, string $PaymentDateEnd): DoctorFeeByPaymentDateDataDto
    {
        $accessKey = config("simrs.access_key");
        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/MobileWS.asmx/ParamedicFeeByParamedicIDPaymentDate", [
            "AccessKey" => $accessKey,
            "ParamedicID" => $paramedicId,
            "PaymentDateStart" => convert_date_to_req_param($paymentDateStart),
            "PaymentDateEnd" => convert_date_to_req_param($PaymentDateEnd)
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Failed connecting to SIMRS", 500);
        }

        $data = $response->json();

        return DoctorFeeByPaymentDateDataDto::from($data);
    }

    public function getInpatientList(string $paramedicId, string $roomName, int $count = 10): InpatientListDataDto
    {
        $accessKey = config("simrs.access_key");
        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/MobileWS2.asmx/RegistrationGetListIpByParamedicID", [
            "AccessKey" => $accessKey,
            "GuarantorID" => "",
            "ParamedicID" => $paramedicId,
            "ClassID" => "",
            "RoomID" => $roomName,
            "EwsStatus" => "",
            "RecordCount" => $count,
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Failed connecting to SIMRS", 500);
        }

        $data = $response->json();

        return InpatientListDataDto::from($data);
    }

    public function getPatientRegistrationCPPT(string $registrationNo): PatientRegistrationCPPTDataDto
    {
        $accessKey = config("simrs.access_key");
        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false,
        ])->get(config("simrs.base_url") . "/MobileWS.asmx/RegistrationCPPT", [
            "AccessKey" => $accessKey,
            "RegistrationNo" => $registrationNo
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Failed connecting to SIMRS", 500);
        }

        $data = $response->json();

        return PatientRegistrationCPPTDataDto::from($data);
    }

    public function getAppointments(string $paramedicId, string $appointmentDate): AppointmentDataDto
    {
        $accessKey = config("simrs.access_key");
        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false,
        ])->get(config("simrs.base_url") . "/MobileWS2.asmx/AppointmentGetListByParamedicIDAppointmentDate", [
            "AccessKey" => $accessKey,
            "ParamedicID" => $paramedicId,
            "AppointmentDate" => $appointmentDate
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Failed connecting to SIMRS", 500);
        }

        $data = $response->json();

        return AppointmentDataDto::from($data);
    }

    public function getAppointmentDetail(string $appointmentNo): AppointmentDetailDataDto
    {
        $accessKey = config("simrs.access_key");
        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false,
        ])->get(config("simrs.base_url") . "/V1_1/AppointmentWS.asmx/AppointmentGetOne", [
            "AccessKey" => $accessKey,
            "AppointmentNo" => $appointmentNo
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Failed connecting to SIMRS", 500);
        }

        $data = $response->json();

        return AppointmentDetailDataDto::from($data);
    }
}
