<?php

namespace App\Services\SimrsService\PatientService;


use App\Dto\SimrsDto\Patient\DoctorScheduleDataDto;
use App\Dto\SimrsDto\Patient\PatientAppointmentListDataDto;
use App\Dto\SimrsDto\Patient\PatientAppointmentListDetailDto;
use App\Dto\SimrsDto\Patient\PatientDataDto;
use App\Dto\SimrsDto\Patient\PatientEncounterDataDto;
use App\Dto\SimrsDto\Patient\PatientEncounterDetailDataDto;
use App\Dto\SimrsDto\Patient\PatientEncounterDto;
use App\Dto\SimrsDto\Patient\PatientLabResultDataDto;
use App\Dto\SimrsDto\Patient\PatientLabResultDetailDataDto;
use App\Dto\SimrsDto\Patient\PatientPrescriptionHistoryDataDto;
use App\Dto\SimrsDto\Patient\PatientPrescriptionHistoryDetailDataDto;
use App\Dto\SimrsDto\Patient\PatientVitalSignHistoryDataDto;
use App\Dto\SimrsDto\Patient\ServiceUnitDataDto;
use App\Models\User;

interface IPatientService
{
    public function getPatients(User $user): PatientDataDto;
    public function getVitalSignHistory(string $type = "", int $count, string $medicalNo): PatientVitalSignHistoryDataDto;
    public function getPatientFamilies(string $ssn, string $phoneNumber): PatientDataDto;
    public function getPrescriptionHistory(int $count = 10, string $medicalNo): PatientPrescriptionHistoryDataDto;
    public function getPrescriptionHistoryDetail(string $prescriptionNo): PatientPrescriptionHistoryDetailDataDto;
    public function getLabResult(string $medicalNo): PatientLabResultDataDto;
    public function getLabResultDetail(string $transactionNo): PatientLabResultDetailDataDto;
    public function getEncounterList(string $medicalNo, string $serviceUniId, string $paramedicId, string $dateStart, string $dateEnd): PatientEncounterDataDto;
    public function getEncounterListDetail(string $registrationNo, string $serviceUniId, string $paramedicId): PatientEncounterDetailDataDto;
    public function getDoctorSchedule(string $dateStart, string $dateEnd, string $serviceUnitID, string $paramedicID): DoctorScheduleDataDto;
    public function getServiceUnitList(string $serviceUnitId, string $serviceUnitName): ServiceUnitDataDto;
}
