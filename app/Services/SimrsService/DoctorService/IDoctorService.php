<?php

namespace App\Services\SimrsService\DoctorService;

use App\Dto\SimrsDto\Doctor\AppointmentDataDto;
use App\Dto\SimrsDto\Doctor\AppointmentDetailDataDto;
use App\Dto\SimrsDto\Doctor\DoctorDataDto;
use App\Dto\SimrsDto\Doctor\DoctorFeeByPaymentDateDataDto;
use App\Dto\SimrsDto\Doctor\DoctorFeeByTrxDateDataDto;
use App\Dto\SimrsDto\Doctor\DoctorSummaryFeeDataDto;
use App\Dto\SimrsDto\Doctor\InpatientListDataDto;
use App\Dto\SimrsDto\Doctor\PatientRegistrationCPPTDataDto;
use App\Dto\SimrsDto\Doctor\InpatientRoomListDataDto;
use App\Dto\SimrsDto\Doctor\PatientGuarantorDataSummaryDto;

interface IDoctorService
{
    public function getDoctors(string $doctorId): DoctorDataDto;

    public function getOverviewSummaryFee(string $paramedicId, string $startDate, string $endDate): DoctorSummaryFeeDataDto;

    public function getFeeByTrxDate(string $paramedicId, string $startDate, string $endDate): DoctorFeeByTrxDateDataDto;

    public function getFeeByPaymentDate(string $paramedicId, string $paymentDateStart, string $PaymentDateEnd): DoctorFeeByPaymentDateDataDto;

    public function getInpatientList(string $paramedicId, string $roomName, int $count = 10): InpatientListDataDto;

    public function getPatientRegistrationCPPT(string $registrationNo): PatientRegistrationCPPTDataDto;

    public function getAppointments(string $paramedicId, string $appointmentDate): AppointmentDataDto;

    public function getAppointmentDetail(string $appointmentNo): AppointmentDetailDataDto;
    
    public function getInpatientRooms(): InpatientRoomListDataDto;

    public function getPatientGuarantorSummary(string $paramedicID): PatientGuarantorDataSummaryDto;
    
}
