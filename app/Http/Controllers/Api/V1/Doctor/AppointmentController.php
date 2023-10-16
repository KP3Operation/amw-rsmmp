<?php

namespace App\Http\Controllers\Api\V1\Doctor;

use App\Http\Controllers\Controller;
use App\Services\SimrsService\DoctorService\IDoctorService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{

    private IDoctorService $doctorService;

    public function __construct(IDoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    public function index(Request $request)
    {
        $response = new \stdClass();
        if (!$request->has('date') || $request->date == '') {
            throw new \HttpRequestException("Invalid date");
        }

        $user = $request->user();
        $userDoctor = $user->userDoctorData;
        $appointments = $this->doctorService->getAppointments($userDoctor->doctor_id, $request->date);

        $response->appointments = $appointments->data;

        return response()->json($response);
   }

    public function show(Request $request)
    {
        $response = new \stdClass();
        if (!$request->has('appointment_no') || $request->appointment_no == '') {
            throw new \HttpRequestException("Invalid date");
        }

        $user = $request->user();
        $userDoctor = $user->userDoctorData;
        $appointments = $this->doctorService->getAppointmentDetail($request->appointment_no);

        $response->appointment = $appointments->data;

        return response()->json($response);
    }
}
