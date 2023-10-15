<?php

namespace App\Http\Controllers\Api\V1\Patient;

use App\Http\Controllers\Controller;
use App\Services\SimrsService\PatientService\IPatientService;
use Illuminate\Http\Request;
use stdClass;

class DoctorScheduleController extends Controller
{
    private IPatientService $patientService;

    public function __construct(IPatientService $patientService)
    {
        $this->patientService = $patientService;
    }
    public function index(Request $request)
    {
        $paramedicId = "";
        $date = "";
        $serviceUnitId = "";
        $response = new stdClass();

        if ($request->has('paramedic_id')) {
            $paramedicId = $request->paramedic_id;
        }

        if ($request->has('date')) {
            $date = $request->date;
        }

        if ($request->has('service_unit_id')) {
            $serviceUnitId = $request->service_unit_id;
        }

        $doctorSchedules = $this->patientService->getDoctorSchedule(
            $date,
            $date,
            $serviceUnitId ?? "",
            $paramedicId ?? ""
        );

        $serviceUnits = $this->patientService->getServiceUnitList('', '');

        $response->schedules = $doctorSchedules->data;
        $response->service_units = $serviceUnits->data;

        return response()->json($response);
    }
}
