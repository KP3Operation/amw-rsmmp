<?php

namespace App\Services\SimrsService\DoctorService;


use App\Dto\SimrsDto\DoctorDataDto;
use App\Models\User;

interface IDoctorService
{
    public function getDoctors(string $doctorId): DoctorDataDto;
}
