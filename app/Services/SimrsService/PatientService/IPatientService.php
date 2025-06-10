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
use App\Dto\SimrsDto\Patient\PatientRadResultDataDto;
use App\Dto\SimrsDto\Patient\PatientRadResultDetailDataDto;
use App\Dto\SimrsDto\Patient\PatientVitalSignHistoryDataDto;
use App\Dto\SimrsDto\Patient\ServiceUnitDataDto;
use App\Models\Simrs\Patient\CreateAppointment;
use Illuminate\Http\JsonResponse;

interface IPatientService
{
    public function getPatients(string $phoneNumber, string $ssn, ?bool $isPersist = true): PatientDataDto;

    // FIXME: Need to reposition the params
    public function getVitalSignHistory(string $type, int $count, string $medicalNo): PatientVitalSignHistoryDataDto;

    public function getPatientFamilies(string $ssn, string $phoneNumber): PatientDataDto;

    // FIXME: Need to reposition the params
    public function getPrescriptionHistory(int $count, string $medicalNo): PatientPrescriptionHistoryDataDto;

    public function getPrescriptionHistoryDetail(string $prescriptionNo): PatientPrescriptionHistoryDetailDataDto;

    public function getLabResult(string $medicalNo): PatientLabResultDataDto;

    public function getLabResultDetail(string $transactionNo): PatientLabResultDetailDataDto;

    public function getRadResult(string $medicalNo): PatientRadResultDataDto;

    public function getRadResultDetail(string $transactionNo): PatientRadResultDetailDataDto;


    public function getEncounterList(string $medicalNo, string $serviceUniId, string $paramedicId, string $dateStart, string $dateEnd): PatientEncounterDataDto;

    public function getEncounterListDetail(string $registrationNo, string $serviceUniId, string $paramedicId): PatientEncounterDetailDataDto;

    public function getDoctorSchedule(string $dateStart, string $dateEnd, string $serviceUnitID, string $paramedicID): DoctorScheduleDataDto;

    public function getServiceUnitList(string $serviceUnitId, string $serviceUnitName): ServiceUnitDataDto;

    public function createAppointment(CreateAppointment $createAppointment): CreateAppointmentDataDto;

    public function deleteAppointment(string $appointmentNo): bool;

    public function getAppointments(string $medicalNo): AppointmentDataDto;

    public function getAppointment(string $appointmentNo): AppointmentDto;

    public function getPatientsByMedicalNo(string $medicalNo): PatientDataDto;

    public function cancelAppointment(string $appointmentNo, string $cancelNote):JsonResponse;
}
