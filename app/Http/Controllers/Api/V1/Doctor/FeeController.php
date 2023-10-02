<?php

namespace App\Http\Controllers\Api\v1\Doctor;

use App\Http\Controllers\Controller;
use App\Services\SimrsService\DoctorService\IDoctorService;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    private IDoctorService $doctorService;

    public function __construct(IDoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    public function getSummaryFee(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $user = $request->user();
        $userDoctor = $user->userDoctorData();

        //$summaryFee = $this->doctorService->getSummaryFee($userDoctor->doctor_id, $request->start_date, $request->end_date);

        return response()->json([
            'data' => [
                'pending' => '6',
                'payout' => '8500000'
            ]
        ]);
    }
}
