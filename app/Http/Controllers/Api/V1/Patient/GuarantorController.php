<?php

namespace App\Http\Controllers\Api\V1\Patient;

use App\Http\Controllers\Controller;
use App\Services\SimrsService\PatientService\IPatientService;
use Illuminate\Http\Request;
use stdClass;

class GuarantorController extends Controller
{
    private IPatientService $patientService;

    public function __construct(IPatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    // public function index(Request $request)
    // {
    //     $GuarantorID = '';
    //     $response = new stdClass();

    //     // if ($request->has('guarantor_id')) {
    //     //     $guarantorId = $request->guarantor_id;
    //     // }

    //     $guarantorLists = $this->patientService->getGuarantorList(
    //         $GuarantorID ?? '',
    //         $guarantorName ?? ''
    //     );

    //     $response->guarantor = $guarantorLists;

    //     return response()->json($response);
    // }

    public function index(Request $request)
    {
        $GuarantorID   = $request->guarantor_id ?? '';
        $guarantorName = $request->guarantor_name ?? '';

        // Ambil data dari service
        $guarantorLists = $this->patientService->getGuarantorList(
            $GuarantorID,
            $guarantorName
        );

        // Pastikan tidak ada array bersarang
        $guarantorLists = collect($guarantorLists)
            ->flatten(1) // hilangkan 1 tingkat array
            ->filter(function ($item) {
                $name = data_get($item, 'guarantorName');
                return stripos($name, 'BPJS') === false;
            })
            ->sortBy('guarantorName')
            ->values()
            ->all();

        // Bungkus dengan format sesuai kebutuhan
        return response()->json([
            'guarantor' => [
                'data' => $guarantorLists
            ]
        ]);
    }
}
