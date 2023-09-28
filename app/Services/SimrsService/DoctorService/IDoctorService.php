<?php

namespace App\Services\SimrsService\DoctorService;


use App\Dto\SimrsDto\Doctor\DoctorAppointmentListDataDto;
use App\Dto\SimrsDto\Doctor\DoctorAppointmentListDetailDto;
use App\Dto\SimrsDto\Doctor\DoctorDataDto;
use App\Dto\SimrsDto\Doctor\DoctorFeeDataDto;

interface IDoctorService
{
    public function getDoctors(string $doctorId): DoctorDataDto;
    public function getAppointmentList(string $AppointmentNo): DoctorAppointmentListDataDto;
    public function getAppointmentListDetail(string $appointmentNo): DoctorAppointmentListDetailDto;
    public function getFee(string $AppointmentNo): DoctorFeeDataDto;
}
