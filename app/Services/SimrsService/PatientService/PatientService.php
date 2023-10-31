<?php

namespace App\Services\SimrsService\PatientService;


use App\Dto\SimrsDto\Patient\AppointmentDataDto;
use App\Dto\SimrsDto\Patient\CreateAppointmentDataDto;
use App\Dto\SimrsDto\Patient\DoctorScheduleDataDto;
use App\Dto\SimrsDto\Patient\PatientDataDto;
use App\Dto\SimrsDto\Patient\PatientEncounterDataDto;
use App\Dto\SimrsDto\Patient\PatientEncounterDetailDataDto;
use App\Dto\SimrsDto\Patient\PatientLabResultDataDto;
use App\Dto\SimrsDto\Patient\PatientLabResultDetailDataDto;
use App\Dto\SimrsDto\Patient\PatientPrescriptionHistoryDataDto;
use App\Dto\SimrsDto\Patient\PatientPrescriptionHistoryDetailDataDto;
use App\Dto\SimrsDto\Patient\PatientVitalSignHistoryDataDto;
use App\Dto\SimrsDto\Patient\ServiceUnitDataDto;
use App\Models\Simrs\Patient\CreateAppointment;
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

        if( count($data['data']) < 1) {
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
                "PhoneNo" => "",
                "Ssn" => $ssn,
                "Email" => ""
            ]);
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
        ])->get(config("simrs.base_url") . "/MobileWS.asmx/PatientLabResultGetOne", [
            "AccessKey" => $accessKey,
            "TransactionNo" => $transactionNo
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Can't communicate with SIMRS.", 500);
        }

        $data = $response->json();

        return PatientLabResultDetailDataDto::from($data);
    }

    public function getEncounterList(string $medicalNo, string $serviceUniId, string $paramedicId, string $dateStart, string $dateEnd): PatientEncounterDataDto
    {
        $accessKey = config("simrs.access_key");

        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/MobileWS2.asmx/PatientRegistrationHistory", [
            "AccessKey" => $accessKey,
            "MedicalNo" => $medicalNo,
            "ServiceUnitID" => $serviceUniId,
            "ParamedicID" => $paramedicId,
            "DateStart" => $dateStart,
            "DateEnd" => $dateEnd,
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Can't communicate with SIMRS.", 500);
        }

        $data = $response->json();

        return PatientEncounterDataDto::from($data);
    }

    public function getEncounterListDetail(string $registrationNo, string $serviceUniId, string $paramedicId): PatientEncounterDetailDataDto
    {
        $accessKey = config("simrs.access_key");

        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/MobileWS2.asmx/RegistrationGetOne", [
            "AccessKey" => $accessKey,
            "RegistrationNo" => $registrationNo,
            "ServiceUnitID" => $serviceUniId,
            "ParamedicID" => $paramedicId
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Can't communicate with SIMRS.", 500);
        }

        $data = $response->json();

        return PatientEncounterDetailDataDto::from($data);
    }

    public function getDoctorSchedule(string $dateStart, string $dateEnd, string $serviceUnitID, string $paramedicID): DoctorScheduleDataDto
    {
        $accessKey = config("simrs.access_key");

        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/MobileWS2.asmx/ParamedicScheduleDateGetList", [
            "AccessKey" => $accessKey,
            "DateStart" => $dateStart,
            "DateEnd" => $dateEnd,
            "ServiceUnitID" => $serviceUnitID,
            "ParamedicID" => $paramedicID
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Can't communicate with SIMRS.", 500);
        }

        $data = $response->json();

        return DoctorScheduleDataDto::from($data);
    }

    public function getServiceUnitList(string $serviceUnitId, string $serviceUnitName): ServiceUnitDataDto
    {
        $accessKey = config("simrs.access_key");

        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/MobileWS2.asmx/ServiceUnitGetList", [
            "AccessKey" => $accessKey,
            "ServiceUnitID" => $serviceUnitId,
            "ServiceUnitName" => $serviceUnitId
        ]);

        if (!$response->successful()) {
            throw new HttpClientException("Can't communicate with SIMRS.", 500);
        }

        $data = $response->json();

        return ServiceUnitDataDto::from($data);
    }

    public function createAppointment(CreateAppointment $createAppointment): CreateAppointmentDataDto
    {
        $accessKey = config("simrs.access_key");

        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/V1_1/AppointmentWS.asmx/AppointmentCreate", [
            "AccessKey" => $accessKey,
            "ServiceUnitID" => $createAppointment->serviceUnitID,
            "ParamedicID" => $createAppointment->paramedicID,
            "AppointmentDate" => $createAppointment->appointmentDate,
            "AppointmentTime" => $createAppointment->appointmentTime,
            "PatientID" => $createAppointment->patientID,
            "FirstName" => $createAppointment->firstName,
            "MiddleName" => $createAppointment->middleName != null ? $createAppointment->middleName : "",
            "LastName" => $createAppointment->lastName != null ? $createAppointment->lastName : "",
            "DateOfBirth" => $createAppointment->dateOfBirth,
            "Sex" => $createAppointment->sex,
            "StreetName" => $createAppointment->streetName != null ? $createAppointment->streetName : "",
            "Email" => $createAppointment->email,
            "GuarantorID" => $createAppointment->guarantorID,
            "District" => $createAppointment->district != null ? $createAppointment->district :  "",
            "County" => $createAppointment->county != null ? $createAppointment->county :  "",
            "City" => $createAppointment->city != null ? $createAppointment->city :  "",
            "State" => $createAppointment->state != null ? $createAppointment->state :  "",
            "ZipCode" => $createAppointment->zipCode != null ? $createAppointment->zipCode :  "",
            "PhoneNo" => $createAppointment->phoneNo != null ? $createAppointment->phoneNo : "",
            "Notes" => $createAppointment->notes != null ? $createAppointment->notes :  "",
            "BirthPlace" => $createAppointment->birthPlace != null ? $createAppointment->birthPlace : "",
            "Ssn" => $createAppointment->ssn != null ? $createAppointment->ssn :  "",
            "MobilePhoneNo" => $createAppointment->mobilePhoneNo != null ? $createAppointment->mobilePhoneNo :  "",
        ]);

        // TODO: Need to show error message from SIMRS
        if (!$response->successful()) {
            throw new HttpClientException("Can't communicate with SIMRS.", 500);
        }

        $data = $response->json();

        if ($data['status'] == 'ERR') {
            throw new \Exception($data['data']);
        }

        return CreateAppointmentDataDto::from($data);
    }

    public function deleteAppointment(string $appointmentNo): bool
    {
        $accessKey = config("simrs.access_key");

        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/MobileWS2.asmx/AppointmentCancel", [
            "AccessKey" => $accessKey,
            "AppointmentNo" => $appointmentNo,
        ]);

        // TODO: Need to show error message from SIMRS
        if (!$response->successful()) {
            throw new HttpClientException("Can't communicate with SIMRS.", 500);
        }

        return true;
    }

    public function getAppointments(string $medicalNo): AppointmentDataDto
    {
        $accessKey = config("simrs.access_key");

        if (!$medicalNo) {
            throw new \Exception("Pasien tidak memiliki No. RM");
        }

        $response = Http::withHeaders([
            'Content-Type' => ""
        ])->withOptions([
            "verify" => false
        ])->get(config("simrs.base_url") . "/AppointmentWS.asmx/AppointmentGetListByMedicalNo", [
            "AccessKey" => $accessKey,
            "MedicalNo" => $medicalNo, // "087463" -> test data
        ]);

        // TODO: Need to show error message from SIMRS
        if (!$response->successful()) {
            throw new HttpClientException("Can't communicate with SIMRS.", 500);
        }

        $data = $response->json();

        if ($data['status'] == 'ERR') {
            throw new \Exception($data['data']);
        }

        return AppointmentDataDto::from($data);
    }
}
