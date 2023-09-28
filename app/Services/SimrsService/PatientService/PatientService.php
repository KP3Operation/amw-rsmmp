<?php

namespace App\Services\SimrsService\PatientService;


use App\Dto\SimrsDto\PatientDataDto;
use App\Dto\SimrsDto\PatientVitalSignHistoryDataDto;
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
        $ssn = $ssn;
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
}
