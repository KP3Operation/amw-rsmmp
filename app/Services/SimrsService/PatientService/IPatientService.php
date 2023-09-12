<?php

namespace App\Services\SimrsService\PatientService;


use App\Dto\SimrsDto\PatientDataDto;
use App\Models\User;

interface IPatientService
{
    public function getPatient(User $user): PatientDataDto;
}
