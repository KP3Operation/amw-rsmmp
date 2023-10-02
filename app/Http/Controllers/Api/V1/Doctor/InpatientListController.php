<?php

namespace App\Http\Controllers\Api\v1\Doctor;

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
        $userDoctor = $user->userDoctorData();

//        $inpatientList = $this->doctorService
//            ->getInpatientList($userDoctor->doctor_id, 10);

        $dummyData = file_get_contents(public_path('dummydata/inpatientlist.json'));
        $dummyData = json_decode($dummyData);

        return response()->json([
            'patients' => $dummyData->data
        ]);
    }
}
