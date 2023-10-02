<?php

namespace App\Http\Controllers\Api\V1\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\Patient\AppointmentListDetailResource;
use App\Http\Resources\Patient\AppointmentListResource;
use App\Http\Resources\Patient\LabResultDetailResource;
use App\Http\Resources\Patient\LabResultResource;
use App\Http\Resources\Patient\PrescriptionHistoryDetailResource;
use App\Http\Resources\Patient\PrescriptionHistoryResource;
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
            throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");

        $vitalSignHistoryData = $this->patientService->getVitalSignHistory(3, $user->userPatientData->medical_no);

        $vitalSignResource = new stdClass();
        $vitalSignResource->data = $vitalSignHistoryData->data;
        $vitalSignResource->patient = $user;
        $vitalSignResource->patient->patient_data = $user->userPatientData;

        return new VitalSignHistoryResource($vitalSignResource);
    }

    public function prescriptionHistory(Request $request): PrescriptionHistoryResource
    {
        //
    }

    public function prescriptionHistoryDetail(Request $request, string $prescriptionNo): PrescriptionHistoryDetailResource
    {
        //
    }

    public function labResult(Request $request): LabResultResource
    {
        //
    }

    public function labResultDetail(Request $request, string $transactionNo): LabResultDetailResource
    {
        //
    }

    public function appointmentList(Request $request): AppointmentListResource
    {
        //
    }

    public function appointmentListDetail(Request $request, string $appointmentNo): AppointmentListDetailResource
    {
        //
    }

}
