<?php

namespace App\Http\Controllers\Api\V1\Doctor;

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

    public function getOverviewSummaryFee(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $user = $request->user();
        $userDoctor = $user->userDoctorData;

        $summaryFee = $this->doctorService->getOverviewSummaryFee($userDoctor->doctor_id, $request->start_date, $request->end_date);
        $feeByTrxDate = $this->doctorService
            ->getFeeByTrxDate($userDoctor->doctor_id, $request->start_date, $request->end_date);

        $payout = '0';
        $pendings = [];
        foreach ($feeByTrxDate->data as $item) {
            if ($item->paymentPercentage != 100) {
                $pendings[] = $item;
            }
        }

        foreach ($summaryFee->data->data as $item) {
            if ($item->name == 'Fee4ServicePaid') {
                $payout = $item->value;
            }
        }

        return response()->json([
            'data' => [
                'payout' => $payout,
                'pending' => count($pendings),
            ],
        ]);
    }

    public function getFeeByTrxDate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $user = $request->user();
        $userDoctor = $user->userDoctorData;

        $feeByTrxDate = $this->doctorService
            ->getFeeByTrxDate($userDoctor->doctor_id, $request->start_date, $request->end_date);
        $feeByPaymentDate = $this->doctorService->getFeeByPaymentDate(
            $userDoctor->doctor_id,
            $request->start_date,
            $request->end_date
        );

        $data = [
            'payouts' => [],
            'pendings' => [],
        ];
        foreach ($feeByPaymentDate->data as $item) {
            foreach ($item->transaction as $trx) {
                $data['payouts'][] = $trx;
            }
        }

        foreach ($feeByTrxDate->data as $item) {
            if ($item->paymentPercentage < 100) {
                $data['pendings'][] = $item;
            }
        }

        return response()->json([
            'data' => $data,
        ]);
    }
}
