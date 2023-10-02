<?php

namespace App\Services\SimrsService\DoctorService;


use App\Dto\SimrsDto\Doctor\DoctorAppointmentListDataDto;
use App\Dto\SimrsDto\Doctor\DoctorAppointmentListDetailDto;
use App\Dto\SimrsDto\Doctor\DoctorDataDto;
use App\Dto\SimrsDto\Doctor\DoctorFeeByTrxDateDataDto;
use App\Dto\SimrsDto\Doctor\DoctorSummaryFeeDataDto;

interface IDoctorService
{
    public function getDoctors(string $doctorId): DoctorDataDto;
    public function getOverviewSummaryFee(string $paramedicId, string $startDate, string $endDate): DoctorSummaryFeeDataDto;
    public function getFeeByTrxDate(string $paramedicId, string $startDate, string $endDate): DoctorFeeByTrxDateDataDto;
}
