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
        $prevData = [];
        $response = [];
        if ($request->has('prev_data') && $request->prev_data != null) {
            $prevData = $request->get('prev_data');
        }

        $inpatientList = $this->doctorService
            ->getInpatientList($userDoctor->doctor_id, $request->room_name ?? '', 150);

        if (count($prevData) >= 10) {
            foreach ($inpatientList->data as $patient) {
                if (!in_array($patient->medicalNo, $prevData)) {
                    $response[] = $patient;
                }

                if (count($response) > (count($prevData) + count($response))) {
                    break;
                }
            }
        } else {
            foreach ($inpatientList->data as $patient) {
                $response[] = $patient;
                if (count($response) >= 10) {
                    break;
                }
            }
        }

        return response()->json([
            'patients' => $response,
        ]);
    }

    public function getPatientRegistrationCPPT(Request $request)
    {
        $request->validate([
            'registration_no' => 'required|string',
        ]);

        $patientRegistrationCPPTs = $this->doctorService->getPatientRegistrationCPPT($request->registration_no);

        return response()->json([
            'cppts' => $patientRegistrationCPPTs->data,
        ]);
    }
}
