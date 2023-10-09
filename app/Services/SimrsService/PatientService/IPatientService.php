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

interface IPatientService
{
    public function getPatients(User $user): PatientDataDto;
    public function getVitalSignHistory(string $type = "", int $count, string $medicalNo): PatientVitalSignHistoryDataDto;
    public function getPatientFamilies(string $ssn, string $phoneNumber): PatientDataDto;
    public function getPrescriptionHistory(int $count = 10, string $medicalNo): PatientPrescriptionHistoryDataDto;
    public function getPrescriptionHistoryDetail(string $prescriptionNo): PatientPrescriptionHistoryDetailDataDto;
    public function getLabResult(string $medicalNo): PatientLabResultDataDto;
    public function getLabResultDetail(string $transactionNo): PatientLabResultDetailDataDto;
    public function getAppointmentList(string $AppointmentNo): PatientAppointmentListDataDto;
    public function getAppointmentListDetail(string $appointmentNo): PatientAppointmentListDetailDto;
}
