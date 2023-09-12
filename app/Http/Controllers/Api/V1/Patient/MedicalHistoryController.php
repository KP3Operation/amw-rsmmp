<?php

namespace App\Http\Controllers\Api\V1\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\Patient\VitalSignHistoryResource;
use App\Models\User;
use App\Services\SimrsService\PatientService\IPatientService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use stdClass;

class MedicalHistoryController extends Controller
{
    private IPatientService $patientService;
    public function __construct(IPatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    public function vitalSign(Request $request): VitalSignHistoryResource
    {
        $user = User::where('id', '=', $request->user()->id)->first();
        if (!$user->userPatientData->medical_no)
            throw new ModelNotFoundException("Tidak ada Medical No. untuk pasien {$user->name}");

        $vitalSignHistoryData = $this->patientService->getVitalSignHistory(3, $user->userPatientData->medical_no);

        $vitalSignResource = new stdClass();
        $vitalSignResource->data = $vitalSignHistoryData->data;
        $vitalSignResource->patient = $user;
        $vitalSignResource->patient->patient_data = $user->userPatientData;

        return new VitalSignHistoryResource($vitalSignResource);
    }
}
