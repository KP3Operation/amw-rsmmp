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
use App\Services\SimrsService\DoctorService\IDoctorService;
use App\Services\SimrsService\PatientService\IPatientService;
use Illuminate\Validation\ValidationException;

class FamilyController extends Controller
{
    private IPatientService $patientService;
    private IDoctorService $doctorService;

    public function __construct(IPatientService $patientService, IDoctorService $doctorService)
    {
        $this->patientService = $patientService;
        $this->doctorService = $doctorService;
    }

    public function index()
    {
        $user = User::findOrFail(auth()->user()->id);
        $serviceUnitLists = $this->patientService->getServiceUnitList('', '');
        $paramedics = $this->doctorService->getDoctors('');
        return response()->json([
            'families' => $user->families,
            'service_units' => $serviceUnitLists->data,
            'paramedics' => $paramedics->data
        ]);
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
        $familySimrsDataFirstAttempt = $this->patientService->getPatientFamilies($family->ssn, $family->phone_number);
        $familyData = $familySimrsDataFirstAttempt->data->first();

        if (!$familyData) {
            $familySimrsDataSecondAttempt = $this->patientService->getPatientFamilies($family->ssn, '');
            $familyData = $familySimrsDataSecondAttempt->data->first();

            if (!$familyData) {
                throw  new \Exception("Data keluarga tidak sesuai dengan data pada SIMRS");
            }
        }

        $family->update($request->only(
            "ssn",
            "name",
            "phone_number",
            "gender",
            "birth_date",
            "email"
        ) + [
                "user_id" => auth()->user()->id,
                "guarantor_id" => $familyData->guarantorId,
                "guarantor_name" => null, // for now we only save null
                "patient_id" => $familyData->patientId,
                "medical_no" => $familyData->medicalNo
        ]);

//        if ($request->has('patient_id')) {
//            $family->update([
//                'patient_id' => $request->patient_id
//            ]);
//        }
//
//        if ($request->has('medical_no')) {
//            $family->update([
//                'medical_no' => $request->medical_no
//            ]);
//        }

        return new UpdateFamilyResource($family);
    }

    public function destroy(Family $family)
    {
        $family->delete();
        return response()->json([], 204);
    }

    public function fetchFamilyDataInSimrs(Family $family)
    {
        $responseFirstAttempt = $this->patientService->getPatientFamilies($family->ssn, $family->phone_number);
        $patientData = $responseFirstAttempt->data->first();

        if (!$patientData) {
            // TODO: Are need to check the patient with only phone number
            $responseSecondtAttempt = $this->patientService->getPatientFamilies($family->ssn, '');
            $patientData = $responseSecondtAttempt->data->first();

            if (!$patientData) {
                throw ValidationException::withMessages(["name" => "Pasien tidak terdaftar di rumah sakit."]);
            }
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
