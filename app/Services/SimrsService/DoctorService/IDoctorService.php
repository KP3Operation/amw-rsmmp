<?php

namespace App\Services\SimrsService\DoctorService;


use App\Dto\SimrsDto\Doctor\DoctorAppointmentListDataDto;
use App\Dto\SimrsDto\Doctor\DoctorAppointmentListDetailDto;
use App\Dto\SimrsDto\Doctor\DoctorDataDto;
use App\Dto\SimrsDto\Doctor\DoctorFeeByPaymentDateDataDto;
use App\Dto\SimrsDto\Doctor\DoctorFeeByTrxDateDataDto;
use App\Dto\SimrsDto\Doctor\DoctorSummaryFeeDataDto;
use App\Dto\SimrsDto\Doctor\InpatientListDataDto;
use App\Dto\SimrsDto\Doctor\PatientRegistrationCPPTDataDto;

interface IDoctorService
{
    public function getDoctors(string $doctorId): DoctorDataDto;
    public function getOverviewSummaryFee(string $paramedicId, string $startDate, string $endDate): DoctorSummaryFeeDataDto;
    public function getFeeByTrxDate(string $paramedicId, string $startDate, string $endDate): DoctorFeeByTrxDateDataDto;
    public function getFeeByPaymentDate(string $paramedicId, string $paymentDateStart, string $PaymentDateEnd): DoctorFeeByPaymentDateDataDto;
    public function getInpatientList(string $paramedicId, array $roomName, int $count = 10): InpatientListDataDto;
    public function getPatientRegistrationCPPT(string $registrationNo): PatientRegistrationCPPTDataDto;
}
