<?php

namespace App\Http\Controllers\Api\v1\Doctor;

use App\Http\Controllers\Controller;
use App\Services\SimrsService\DoctorService\IDoctorService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use function Symfony\Component\Translation\t;

class FeeController extends Controller
{
    private IDoctorService $doctorService;

    public function __construct(IDoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    public function getOverviewSummaryFee(Request $request)
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

    public function getFeeByTrxDate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $user = $request->user();
        $userDoctor = $user->userDoctorData();

//        $feeByTrxDate = $this->doctorService
//            ->getFeeByTrxDate($userDoctor->doctor_id, $request->start_date, $request->end_date);

        $dummyData = file_get_contents(public_path('dummydata/feebytrxdate.json'));
        $dummyData = json_decode($dummyData);

        return response()->json([
            'data' => [
                'payouts' => $dummyData->data,
                'pendings' => $dummyData->data
            ]
        ]);
    }
}
