<?php

namespace App\Services\SimrsService\PatientService;


use App\Dto\SimrsDto\PatientDataDto;
use App\Dto\SimrsDto\PatientVitalSignHistoryDataDto;
use App\Models\User;

interface IPatientService
{
    public function getPatients(User $user): PatientDataDto;
    public function getVitalSignHistory(int $count, string $medicalNo): PatientVitalSignHistoryDataDto;
}
