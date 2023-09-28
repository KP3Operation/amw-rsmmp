<?php

namespace App\Services\SimrsService\PatientService;


use App\Dto\SimrsDto\Patient\PatientAppointmentListDataDto;
use App\Dto\SimrsDto\Patient\PatientAppointmentListDetailDto;
use App\Dto\SimrsDto\Patient\PatientDataDto;
use App\Dto\SimrsDto\Patient\PatientLabResultDataDto;
use App\Dto\SimrsDto\Patient\PatientLabResultDetailDto;
use App\Dto\SimrsDto\Patient\PatientPrescriptionHistoryDataDto;
use App\Dto\SimrsDto\Patient\PatientPrescriptionHistoryDetailDto;
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

    public function getVitalSignHistory(int $count, string $medicalNo): PatientVitalSignHistoryDataDto
    {
        $accessKey = config("simrs.access_key");
        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/MobileWS2.asmx/VitalSignByMedicalNo", [
            "AccessKey" => $accessKey,
            "MedicalNo" => $medicalNo,
            "VitalSignType" => "",
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

    public function getPrescriptionHistory(int $count, string $medicalNo): PatientPrescriptionHistoryDataDto
    {
        // TODO: Implement getPrescriptionHistory() method.
    }

    public function getPrescriptionHistoryDetail(string $prescriptionNo): PatientPrescriptionHistoryDetailDto
    {
        // TODO: Implement getPrescriptionHistoryDetail() method.
    }

    public function getLabResult(string $medicalNo): PatientLabResultDataDto
    {
        // TODO: Implement getLabResult() method.
    }

    public function getLabResultDetail(string $transactionNo): PatientLabResultDetailDto
    {
        // TODO: Implement getLabResultDetail() method.
    }

    public function getAppointmentList(string $AppointmentNo): PatientAppointmentListDataDto
    {
        // TODO: Implement getAppointmentList() method.
    }

    public function getAppointmentListDetail(string $appointmentNo): PatientAppointmentListDetailDto
    {
        // TODO: Implement getAppointmentListDetail() method.
    }
}
