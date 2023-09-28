<?php

namespace App\Http\Controllers\Api\V1\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\StoreFamilyRequest;
use App\Http\Requests\Patient\UpdateFamilyRequest;
use App\Http\Resources\Patient\FamilyResource;
use App\Http\Resources\Patient\StoreFamilyResource;
use App\Http\Resources\Patient\UpdateFamilyResource;
use App\Models\Family;
use App\Models\User;
use App\Services\SimrsService\PatientService\IPatientService;
use Illuminate\Validation\ValidationException;

class FamilyController extends Controller
{
    private IPatientService $patientService;

    public function __construct(IPatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    public function index(): FamilyResource
    {
        $user = User::findOrFail(auth()->user()->id);
        return new FamilyResource($user->families);
    }

    public function store(StoreFamilyRequest $request): StoreFamilyResource
    {
        $family = Family::create($request->only(
            "ssn",
            "name",
            "phone_number",
            "gender",
            "birth_date",
            "email"
        ) + [
            "user_id" => auth()->user()->id,
            "patient_id" => null,
            "medical_no" => null
        ]);

        return new StoreFamilyResource($family);
    }

    public function show(Family $family)
    {
        return response()->json([
            'family' => $family
        ]);
    }

    public function update(UpdateFamilyRequest $request, Family $family): UpdateFamilyResource
    {
        $family->update($request->only(
            "ssn",
            "name",
            "phone_number",
            "gender",
            "birth_date",
            "email"
        ) + [
            "user_id" => auth()->user()->id
        ]);

        if ($request->has('patient_id')) {
            $family->update([
                'patient_id' => $request->patient_id
            ]);
        }

        if ($request->has('medical_no')) {
            $family->update([
                'patient_id' => $request->medical_no
            ]);
        }

        return new UpdateFamilyResource($family);
    }

    public function destroy(Family $family)
    {
        $family->delete();
        return response()->json([], 204);
    }

    public function fetchFamilyDataInSimrs(Family $family)
    {
        $response = $this->patientService->getPatientFamilies($family->ssn, $family->phone_number);
        $patientData = $response->data->first();

        if ($patientData == null) {
            throw ValidationException::withMessages("Pasien tidak terdaftar di rumah sakit.");
        }

        $family->name = $patientData->firstName . " " . $patientData->middleName . " " . $patientData->lastName;
        $family->birth_date = $patientData->birthDate;
        $family->patient_id = $patientData->patientId;
        $family->medical_no = $patientData->medicalNo;
        $family->gender = $patientData->gender;
        $family->email = ($patientData->email == "") ? $family->email : $patientData->email;

        return response()->json([
            'family' => $family
        ]);
    }
}
