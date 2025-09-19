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

    public function index(Request $request)
    {
        $guarantorId = '';
        $response = new stdClass();

        if ($request->has('guarantor_id')) {
            $guarantorId = $request->guarantor_id;
        }

        $guarantorLists = $this->patientService->getGuarantorList(
            $guarantorId ?? '',
            $guarantorName ?? ''
        );

        $response->guarantor = $guarantorLists;

        return response()->json($response);
    }
}
