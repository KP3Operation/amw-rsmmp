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
use Illuminate\Validation\ValidationException;
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

        $vitalSignHistoryData = $this->patientService->getVitalSignHistory($request->type ?? "", 10, $user->userPatientData->medical_no);

        $vitalSignResource = new stdClass();
        $vitalSignResource->data = $vitalSignHistoryData->data;
        $vitalSignResource->patient = $user;
        $vitalSignResource->patient->patient_data = $user->userPatientData;

        return new VitalSignHistoryResource($vitalSignResource);
    }

    public function prescriptionHistory(Request $request): PrescriptionHistoryResource
    {
        $user = User::where('id', '=', $request->user()->id)->first();
        if (!$user->userPatientData->medical_no)
            throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");

        $prescriptionHistories = $this->patientService->getPrescriptionHistory(10, $user->userPatientData->medical_no);

        $vitalSignResource = new stdClass();
        $vitalSignResource->data = $prescriptionHistories->data;
        $vitalSignResource->patient = $user;
        $vitalSignResource->patient->patient_data = $user->userPatientData;

        return new PrescriptionHistoryResource($vitalSignResource);
    }

    public function prescriptionHistoryDetail(Request $request): PrescriptionHistoryDetailResource
    {
        $user = User::where('id', '=', $request->user()->id)->first();
        if (!$user->userPatientData->medical_no)
            throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");

        if (!$request->has('prescription_no')) {
            throw ValidationException::withMessages(['prescription_no' => 'Gagal mengambil data']);
        }

        $prescriptionHistoryDetail = $this->patientService->getPrescriptionHistoryDetail($request->prescription_no);

        $prescriptionHistoryResource = new stdClass();
        $prescriptionHistoryResource->data = $prescriptionHistoryDetail->data;
        $prescriptionHistoryResource->patient = $user;
        $prescriptionHistoryResource->patient->patient_data = $user->userPatientData;

        return new PrescriptionHistoryDetailResource($prescriptionHistoryResource);
    }

    public function labResult(Request $request): LabResultResource
    {
        $user = User::where('id', '=', $request->user()->id)->first();
        if (!$user->userPatientData->medical_no)
            throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");

        $labResults = $this->patientService->getLabResult($user->userPatientData->medical_no);

        $labResultResource = new stdClass();
        $labResultResource->data = $labResults->data;
        $labResultResource->patient = $user;
        $labResultResource->patient->patient_data = $user->userPatientData;

        return new LabResultResource($labResultResource);
    }

    public function labResultDetail(Request $request, string $transactionNo): LabResultDetailResource
    {
        $user = User::where('id', '=', $request->user()->id)->first();
        if (!$user->userPatientData->medical_no)
            throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");

        if (!$request->has('transaction_no')) {
            throw ValidationException::withMessages(['transaction_no' => 'Gagal mengambil data']);
        }

        $labResultsDetail = $this->patientService->getLabResultDetail($request->transaction_no);

        $labResultDetail = new stdClass();
        $labResultDetail->data = $labResultsDetail->data;
        $labResultDetail->patient = $user;
        $labResultDetail->patient->patient_data = $user->userPatientData;

        return new LabResultDetailResource($labResultDetail);
    }

    public function appointmentList(Request $request): AppointmentListResource
    {
        throw new \Exception("Unimplemented");
    }

    public function appointmentListDetail(Request $request, string $appointmentNo): AppointmentListDetailResource
    {
        throw new \Exception("Unimplemented");
    }
}
