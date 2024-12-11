<?php

namespace App\Services\SimrsService\PatientService;

use App\Dto\SimrsDto\Patient\AppointmentDataDto;
use App\Dto\SimrsDto\Patient\AppointmentDto;
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
use App\Exceptions\SimrsException;
use App\Models\Simrs\Patient\CreateAppointment;
use App\Services\SimrsService\ISimrsBaseApi;
use Exception;

use Illuminate\Support\Facades\Log;

class PatientService implements IPatientService
{
    private ISimrsBaseApi $simrsBaseApi;

    public function __construct(ISimrsBaseApi $simrsBaseApi)
    {
        $this->simrsBaseApi = $simrsBaseApi;
    }

    /**
     * @throws SimrsException
     */
    public function getPatients(string $phoneNumber, string $ssn): PatientDataDto
    {
        $phoneNumber = str_replace(config('app.calling_code'), '0', $phoneNumber);
        $response = $this->simrsBaseApi->get('/V1_1/AppointmentWS.asmx/PatientSearchByField', [], [
            'MedicalNo' => '',
            'Name' => '',
            'DateOfBirth' => '',
            'Address' => '',
            'PhoneNo' => $phoneNumber,
            'Ssn' => $ssn,
            'Email' => '',
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        if (count($data['data']) < 1) {
            $response = $this->simrsBaseApi->get('/V1_1/AppointmentWS.asmx/PatientSearchByField', [], [
                'MedicalNo' => '',
                'Name' => '',
                'DateOfBirth' => '',
                'Address' => '',
                'PhoneNo' => '',
                'Ssn' => $ssn,
                'Email' => '',
            ]);
        }

        $data = $response->json();

        return PatientDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getVitalSignHistory(string $type, int $count, string $medicalNo): PatientVitalSignHistoryDataDto
    {
        $response = $this->simrsBaseApi->get('/MobileWS2.asmx/VitalSignByMedicalNo', [], [
            'MedicalNo' => $medicalNo,
            'VitalSignType' => $type,
            'RecordCount' => $count,
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return PatientVitalSignHistoryDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getPatientFamilies(string $ssn, string $phoneNumber): PatientDataDto
    {
        $phoneNumber = str_replace(config('app.calling_code'), '0', $phoneNumber);
        $response = $this->simrsBaseApi->get('/V1_1/AppointmentWS.asmx/PatientSearchByField', [], [
            'MedicalNo' => '',
            'Name' => '',
            'DateOfBirth' => '',
            'Address' => '',
            'PhoneNo' => $phoneNumber,
            'Ssn' => $ssn,
            'Email' => '',
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return PatientDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getPrescriptionHistory(int $count, string $medicalNo): PatientPrescriptionHistoryDataDto
    {
        $response = $this->simrsBaseApi->get('/MobileWS2.asmx/PrescriptionList', [], [
            'MedicalNo' => $medicalNo,
            'RecordCount' => $count,
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return PatientPrescriptionHistoryDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getPrescriptionHistoryDetail(string $prescriptionNo): PatientPrescriptionHistoryDetailDataDto
    {
        $response = $this->simrsBaseApi->get('/MobileWS2.asmx/PrescriptionDetail', [], [
            'PrescriptionNo' => $prescriptionNo,
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return PatientPrescriptionHistoryDetailDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getLabResult(string $medicalNo): PatientLabResultDataDto
    {
        $response = $this->simrsBaseApi->get('/MobileWS2.asmx/PatientLabResultList', [], [
            'MedicalNo' => $medicalNo,'RecordCount' => 20
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return PatientLabResultDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getLabResultDetail(string $transactionNo): PatientLabResultDetailDataDto
    {
        $response = $this->simrsBaseApi->get('/MobileWS.asmx/PatientLabResultGetOne', [], [
            'TransactionNo' => $transactionNo,
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return PatientLabResultDetailDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getEncounterList(string $medicalNo, string $serviceUniId, string $paramedicId, string $dateStart, string $dateEnd): PatientEncounterDataDto
    {
        $response = $this->simrsBaseApi->get('/MobileWS2.asmx/PatientRegistrationHistory', [], [
            'MedicalNo' => $medicalNo,
            'ServiceUnitID' => $serviceUniId,
            'ParamedicID' => $paramedicId,
            'DateStart' => $dateStart,
            'DateEnd' => $dateEnd,
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return PatientEncounterDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getEncounterListDetail(string $registrationNo, string $serviceUniId, string $paramedicId): PatientEncounterDetailDataDto
    {
        $response = $this->simrsBaseApi->get('/MobileWS2.asmx/RegistrationGetOne', [], [
            'RegistrationNo' => $registrationNo,
            'ServiceUnitID' => $serviceUniId,
            'ParamedicID' => $paramedicId,
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return PatientEncounterDetailDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getDoctorSchedule(string $dateStart, string $dateEnd, string $serviceUnitID, string $paramedicID): DoctorScheduleDataDto
    {
        $response = $this->simrsBaseApi->get('/MobileWS2.asmx/ParamedicScheduleDateGetList', [], [
            'DateStart' => $dateStart,
            'DateEnd' => $dateEnd,
            'ServiceUnitID' => $serviceUnitID,
            'ParamedicID' => $paramedicID,
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return DoctorScheduleDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getServiceUnitList(string $serviceUnitId, string $serviceUnitName): ServiceUnitDataDto
    {
        $response = $this->simrsBaseApi->get('/MobileWS2.asmx/ServiceUnitGetList', [], [
            'ServiceUnitID' => $serviceUnitId,
            'ServiceUnitName' => $serviceUnitId,
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return ServiceUnitDataDto::from($data);
    }

    /**
     * @throws SimrsException
     * @throws Exception
     */
    public function createAppointment(CreateAppointment $createAppointment): CreateAppointmentDataDto
    {
        $dataSend = [
            'ServiceUnitID' => $createAppointment->serviceUnitID,
            'ParamedicID' => $createAppointment->paramedicID,
            'AppointmentDate' => $createAppointment->appointmentDate,
            'AppointmentTime' => $createAppointment->appointmentTime,
            'PatientID' => $createAppointment->patientID,
            'FirstName' => $createAppointment->firstName,
            'MiddleName' => $createAppointment->middleName != null ? $createAppointment->middleName : '',
            'LastName' => $createAppointment->lastName != null ? $createAppointment->lastName : '',
            'DateOfBirth' => $createAppointment->dateOfBirth,
            'Sex' => $createAppointment->sex,
            'StreetName' => $createAppointment->streetName != null ? $createAppointment->streetName : '',
            'Email' => $createAppointment->email,
            'GuarantorID' => $createAppointment->guarantorID,
            'District' => $createAppointment->district != null ? $createAppointment->district : '',
            'County' => $createAppointment->county != null ? $createAppointment->county : '',
            'City' => $createAppointment->city != null ? $createAppointment->city : '',
            'State' => $createAppointment->state != null ? $createAppointment->state : '',
            'ZipCode' => $createAppointment->zipCode != null ? $createAppointment->zipCode : '',
            'PhoneNo' => $createAppointment->phoneNo != null ? $createAppointment->phoneNo : '',
            'Notes' => $createAppointment->notes != null ? $createAppointment->notes : '',
            'BirthPlace' => $createAppointment->birthPlace != null ? $createAppointment->birthPlace : '',
            'Ssn' => $createAppointment->ssn != null ? $createAppointment->ssn : '',
            'MobilePhoneNo' => $createAppointment->mobilePhoneNo != null ? $createAppointment->mobilePhoneNo : '',
        ];

        $response = $this->simrsBaseApi->get('/V1_1/AppointmentWS.asmx/AppointmentCreate', [], $dataSend);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        if ($data['status'] == 'ERR') {
            throw new Exception($data['data']);
        }

        return CreateAppointmentDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function deleteAppointment(string $appointmentNo): bool
    {
        $response = $this->simrsBaseApi->get('/MobileWS2.asmx/AppointmentCancel', [], [
            'AppointmentNo' => $appointmentNo,
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        return true;
    }

    /**
     * @throws SimrsException
     * @throws Exception
     */
    public function getAppointments(string $medicalNo): AppointmentDataDto
    {
        if (! $medicalNo) {
            throw new SimrsException('Pasien tidak memiliki No. Rekam Medis');
        }

        $response = $this->simrsBaseApi->get('/AppointmentWS.asmx/AppointmentGetListByMedicalNo', [], [
            'MedicalNo' => $medicalNo,
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        if ($data['status'] == 'ERR') {
            throw new Exception($data['data']);
        }

        return AppointmentDataDto::from($data);
    }

    /**
     * @throws SimrsException
     * @throws Exception
     */
    public function getAppointment(string $appointmentNo): AppointmentDto
    {
        $response = $this->simrsBaseApi->get('/AppointmentWS.asmx/AppointmentGetOne', [], [
            'AppointmentNo' => $appointmentNo,
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        if ($data['status'] == 'ERR') {
            throw new Exception($data['data']);
        }

        return AppointmentDto::from($data['data']);
    }

    public function getPatientsByMedicalNo(string $medicalNo): PatientDataDto
    {
        $response = $this->simrsBaseApi->get('/V1_1/AppointmentWS.asmx/PatientSearchByField', [], [
            'MedicalNo' => $medicalNo,
            'Name' => '',
            'DateOfBirth' => '',
            'Address' => '',
            'PhoneNo' => '',
            'Ssn' => '',
            'Email' => '',
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        if (count($data['data']) < 1) {
            $response = $this->simrsBaseApi->get('/V1_1/AppointmentWS.asmx/PatientSearchByField', [], [
                'MedicalNo' => '',
                'Name' => '',
                'DateOfBirth' => '',
                'Address' => '',
                'PhoneNo' => '',
                'Ssn' => $ssn,
                'Email' => '',
            ]);
        }

        $data = $response->json();

        return PatientDataDto::from($data);
    }
}
