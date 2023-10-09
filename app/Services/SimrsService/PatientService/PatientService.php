<?php

namespace App\Services\SimrsService\PatientService;


use App\Dto\SimrsDto\Patient\PatientAppointmentListDataDto;
use App\Dto\SimrsDto\Patient\PatientAppointmentListDetailDto;
use App\Dto\SimrsDto\Patient\PatientDataDto;
use App\Dto\SimrsDto\Patient\PatientLabResultDataDto;
use App\Dto\SimrsDto\Patient\PatientLabResultDetailDataDto;
use App\Dto\SimrsDto\Patient\PatientPrescriptionHistoryDataDto;
use App\Dto\SimrsDto\Patient\PatientPrescriptionHistoryDetailDataDto;
use App\Dto\SimrsDto\Patient\PatientVitalSignHistoryDataDto;
use App\Models\User;
use App\Models\UserPatient;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Http;

class PatientService implements IPatientService
{
    public function getPatients(User $user): PatientDataDto
    {
        $ssn = "";
        $accessKey = config("simrs.access_key");
        $phoneNumber = str_replace(config('app.calling_code'), "0", $user->phone_number);

        $userPatientData = UserPatient::where("user_id", "=", $user->id)->first();
        if ($userPatientData && $userPatientData->ssn != null) {
            $ssn = $userPatientData->ssn;
        }

        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/V1_1/AppointmentWS.asmx/PatientSearchByField", [
            "AccessKey" => $accessKey,
            "MedicalNo" => "",
            "Name" => "",
            "DateOfBirth" => "",
            "Address" => "",
            "PhoneNo" => $phoneNumber,
            "Ssn" => $ssn,
            "Email" => ""
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Can't communicate with SIMRS.", 500);
        }

        $data = $response->json();
        return PatientDataDto::from($data);
    }

    public function getVitalSignHistory(string $type = "", int $count, string $medicalNo): PatientVitalSignHistoryDataDto
    {
        $accessKey = config("simrs.access_key");
        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/MobileWS2.asmx/VitalSignByMedicalNo", [
            "AccessKey" => $accessKey,
            "MedicalNo" => $medicalNo,
            "VitalSignType" => $type,
            "RecordCount" => $count,
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Can't communicate with SIMRS.", 500);
        }

        $data = $response->json();
        return PatientVitalSignHistoryDataDto::from($data);
    }

    public function getPatientFamilies(string $ssn, string $phoneNumber): PatientDataDto
    {
        $accessKey = config("simrs.access_key");
        $phoneNumber = str_replace(config('app.calling_code'), "0", $phoneNumber);

        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/V1_1/AppointmentWS.asmx/PatientSearchByField", [
            "AccessKey" => $accessKey,
            "MedicalNo" => "",
            "Name" => "",
            "DateOfBirth" => "",
            "Address" => "",
            "PhoneNo" => $phoneNumber,
            "Ssn" => $ssn,
            "Email" => ""
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Can't communicate with SIMRS.", 500);
        }

        $data = $response->json();
        return PatientDataDto::from($data);
    }

    public function getPrescriptionHistory(int $count = 10, string $medicalNo): PatientPrescriptionHistoryDataDto
    {
        $accessKey = config("simrs.access_key");

        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/MobileWS2.asmx/PrescriptionList", [
            "AccessKey" => $accessKey,
            "MedicalNo" => $medicalNo,
            "RecordCount" => $count,
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Can't communicate with SIMRS.", 500);
        }

        $data = $response->json();

        return PatientPrescriptionHistoryDataDto::from($data);
    }

    public function getPrescriptionHistoryDetail(string $prescriptionNo): PatientPrescriptionHistoryDetailDataDto
    {
        $accessKey = config("simrs.access_key");

        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/MobileWS2.asmx/PrescriptionDetail", [
            "AccessKey" => $accessKey,
            "PrescriptionNo" => $prescriptionNo
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Can't communicate with SIMRS.", 500);
        }

        $data = $response->json();

        return PatientPrescriptionHistoryDetailDataDto::from($data);
    }

    public function getLabResult(string $medicalNo): PatientLabResultDataDto
    {
        $accessKey = config("simrs.access_key");

        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/MobileWS.asmx/PatientLabResult", [
            "AccessKey" => $accessKey,
            "MedicalNo" => $medicalNo
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Can't communicate with SIMRS.", 500);
        }

        $data = $response->json();

        return PatientLabResultDataDto::from($data);
    }

    public function getLabResultDetail(string $transactionNo): PatientLabResultDetailDataDto
    {
        $accessKey = config("simrs.access_key");

        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/MobileWS2.asmx/PatientLabResultGetOne", [
            "AccessKey" => $accessKey,
            "TransactionNo" => $transactionNo
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Can't communicate with SIMRS.", 500);
        }

        $data = $response->json();

        return PatientLabResultDetailDataDto::from($data);
    }

    public function getAppointmentList(string $AppointmentNo): PatientAppointmentListDataDto
    {
        throw new \Exception("Unimplemented");
    }

    public function getAppointmentListDetail(string $appointmentNo): PatientAppointmentListDetailDto
    {
        throw new \Exception("Unimplemented");
    }
}
