<?php

namespace App\Http\Controllers\Api\V1\Doctor;

use App\Http\Controllers\Controller;
use App\Services\SimrsService\DoctorService\IDoctorService;
use App\Services\SimrsService\DoctorService\DoctorService;
use Illuminate\Http\Request;

class PatientGuarantorController extends Controller
{
    private IDoctorService $doctorService;
    

    public function __construct(IDoctorService $doctorService )
    {
        $this->doctorService = $doctorService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $userDoctor = $user->userDoctorData;
        
        $result = $this->doctorService
            ->getPatientGuarantorSummary($userDoctor->doctor_id);

        // if (count($prevData) >= 10) {
        //     foreach ($inpatientList->data as $patient) {
        //         if (!in_array($patient->medicalNo, $prevData)) {
        //             $response[] = $patient;
        //         }

        //         if (count($response) > (count($prevData) + count($response))) {
        //             break;
        //         }
        //     }
        // } else {
        //     foreach ($inpatientList->data as $patient) {
        //         $response[] = $patient;
        //         if (count($response) >= 10) {
        //             break;
        //         }
        //     }
        // }

        return response()->json([
            'summary' => $result->data,
        ]);
    }
}
