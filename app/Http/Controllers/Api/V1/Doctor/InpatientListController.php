<?php

namespace App\Http\Controllers\Api\V1\Doctor;

use App\Http\Controllers\Controller;
use App\Services\SimrsService\DoctorService\IDoctorService;
use Illuminate\Http\Request;

class InpatientListController extends Controller
{
    private IDoctorService $doctorService;

    public function __construct(IDoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $userDoctor = $user->userDoctorData;

        $inpatientList = $this->doctorService
            ->getInpatientList($userDoctor->doctor_id, $request->room_name ?? "", 10);

        return response()->json([
            'patients' => $inpatientList->data
        ]);
    }

    public function getPatientRegistrationCPPT(Request $request)
    {
        $request->validate([
            'registration_no' => 'required|string'
        ]);

        $patientRegistrationCPPTs = $this->doctorService->getPatientRegistrationCPPT($request->registration_no);

        return response()->json([
            'cppts' => $patientRegistrationCPPTs->data
        ]);
    }
}
