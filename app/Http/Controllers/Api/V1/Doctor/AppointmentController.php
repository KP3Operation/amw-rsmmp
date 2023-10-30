<?php

namespace App\Http\Controllers\Api\V1\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Services\SimrsService\DoctorService\IDoctorService;
use App\Services\SimrsService\PatientService\IPatientService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{

    private IDoctorService $doctorService;
    private IPatientService $patientService;

    public function __construct(IDoctorService $doctorService, IPatientService $patientService)
    {
        $this->doctorService = $doctorService;
        $this->patientService = $patientService;
    }

    public function index(Request $request)
    {
        $response = new \stdClass();
        if (!$request->has('date') || $request->date == '') {
            throw new \Exception("Invalid date");
        }

        $user = $request->user();
        $userDoctor = $user->userDoctorData;
        $appointments = $this->doctorService->getAppointments($userDoctor->doctor_id, $request->date);

        $response->appointments = $appointments->data;

        return response()->json($response);
   }

   public function getGroupAppointment(Request $request)
   {
       $response = new \stdClass();
       if (!$request->has('date') || $request->date == '') {
           throw new \Exception("Invalid date");
       }

       $user = $request->user();
       $userDoctor = $user->userDoctorData;
       $serviceUnits = $this->patientService->getDoctorSchedule($request->date, $request->date, '', $userDoctor->doctor_id);
       if (!$serviceUnits->data->first()) {
           return response()->json([
               'appointments' => []
           ]);
       }

        //  $appointments = $this->doctorService->getAppointments($userDoctor->doctor_id, '2023-10-24');
       $appointments = $this->doctorService->getAppointments($userDoctor->doctor_id, $request->date);
       $appointmentsDetails = [];
       foreach ($appointments->data as $appointment) {
           $appointmentDetail = $this->doctorService->getAppointmentDetail($appointment->appointmentNo);
           $appointmentsDetails [] = $appointmentDetail->data;
       }

       $groupings = [];
       foreach ($serviceUnits->data as $serviceUnit) {
           foreach ($appointmentsDetails as $appointmentDetail) {
               if ($appointmentDetail->serviceUnitID == $serviceUnit->serviceUnitID) {
                  $groupings[$appointmentDetail->serviceUnitID]['date'] = $request->date;
                  if (!array_key_exists('count', $groupings[$appointmentDetail->serviceUnitID])) {
                      $groupings[$appointmentDetail->serviceUnitID]['count'] = 1;
                  } else {
                      $groupings[$appointmentDetail->serviceUnitID]['count'] += 1;
                  }
                   $groupings[$appointmentDetail->serviceUnitID]['serviceUnitName'] = $appointmentDetail->serviceUnitName;
               }
           }
       }

       $groupings = array_values($groupings);

       $response->appointments = $groupings;

       return response()->json($response);
   }

    public function show(Request $request)
    {
        $response = new \stdClass();
        if (!$request->has('appointment_no') || $request->appointment_no == '') {
            throw new \Exception("Invalid date");
        }

        $user = $request->user();
        $userDoctor = $user->userDoctorData;
        $appointments = $this->doctorService->getAppointmentDetail($request->appointment_no);

        $response->appointment = $appointments->data;

        return response()->json($response);
    }

    public function getOverviewAppointment(Request $request)
    {
        $response = new \stdClass();
        if (!$request->has('date') || $request->date == '') {
            throw new \Exception("Invalid date");
        }

        $data = [];
        $user = $request->user();
        $userDoctor = $user->userDoctorData;
        $appointments = $this->doctorService->getAppointments($userDoctor->doctor_id, $request->date);

//        Appointment::where('')
//        foreach ($appointments->data as $appointment) {
//            if (array_key_exists());
//        }

        $response->appointments = $appointments->data;

        return response()->json($response);

    }
}
