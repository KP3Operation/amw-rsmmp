<?php

namespace App\Http\Controllers\Api\V1\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\DestroyAppointmentRequest;
use App\Http\Requests\Patient\GetAppointmentsRequest;
use App\Http\Requests\Patient\StoreAppointmentRequest;
use App\Models\Family;
use App\Models\Simrs\Patient\CreateAppointment;
use App\Models\User;
use App\Services\SimrsService\PatientService\IPatientService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    private IPatientService $patientService;

    public function __construct(IPatientService $patientService)
    {
        $this->patientService = $patientService;
    }
    public function index(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $medicalNo = $user->userPatientData->medical_no;

        if ($request->has('medical_no') && $request->medical_no != '') {
            $medicalNo = $request->medical_no;
        }

        $appointments = $this->patientService->getAppointments($medicalNo);

        $response = new \stdClass();
        $opens = [];
        $cancels = [];
        $dones = [];

        foreach ($appointments->data as $appointment) {
            if ($appointment->appointmentStatus == '01') { // open
                $opens[] = $appointment;
            } else if ($appointment->appointmentStatus == '02') { // done
                $dones[] = $appointment;
            } else { // cancel
                $cancels[] = $appointment;
            }
        }

        return response()->json([
            'appointments' => [
                'dones' => $dones,
                'opens' => $opens,
                'cancels' => $cancels
            ]
        ]);
    }

    public function store(StoreAppointmentRequest $request)
    {
        $user = User::findOrFail(auth()->user()->id);

        // register it self
        if ($request->patient_id == $user->userPatientData->patient_id) {
            $appointmentData = new CreateAppointment(
                $request->service_unit_id,
                $request->paramedic_id,
                get_date_from_datetime($request->appointment_date),
                get_time_from_datetime($request->appointment_date),
                $user->userPatientData->patient_id,
                $user->name,
                '',
                '',
                $user->userPatientData->birth_date,
                $request->gender === 'Perempuan' ? 'F' : 'M',
                '',
                $user->email,
                'SELF', // TODO: Need to update family to save the guarantor id & name
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            );

            $createdAppointment = $this->patientService->createAppointment($appointmentData);
            $appoinment = $createdAppointment->data;

            // TODO: Need to save to local db
            dd($appoinment);
        } else { // register family member
            $family = Family::where('patient_id', '=', $request->patient_id)->first();
            if (!$family) {
                throw new \Exception("Gagal mengambil data family member");
            }

            $appointmentData = new CreateAppointment(
                $request->service_unit_id,
                $request->paramedic_id,
                get_date_from_datetime($request->appointment_date),
                get_time_from_datetime($request->appointment_date),
                $request->patient_id,
                $family->name,
                '',
                '',
                $family->birth_date,
                $request->gender === 'Perempuan' ? 'F' : 'M',
                '',
                $family->email,
                'SELF', // TODO: Need to update family to save the guarantor id & name
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            );

            $createdAppointment = $this->patientService->createAppointment($appointmentData);
            $appoinment = $createdAppointment->data;

            // TODO: Need to save to local db
            dd($appoinment);
        }
    }

    public function destroy(DestroyAppointmentRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->patientService->deleteAppointment($request->appointment_no);

        return response()->json([], 204);
    }
}
