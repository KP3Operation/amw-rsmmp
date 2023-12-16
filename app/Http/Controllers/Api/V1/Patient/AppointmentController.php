<?php

namespace App\Http\Controllers\Api\V1\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\DestroyAppointmentRequest;
use App\Http\Requests\Patient\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Family;
use App\Models\Notification;
use App\Models\Simrs\Patient\CreateAppointment;
use App\Models\User;
use App\Services\SimrsService\PatientService\IPatientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $serviceUnitId = '';
        $startDate = '';
        $endDate = '';
        $response = [];
        $tempResponse = [];
        $selectedUserId = $user->id;
        $newAppointments = [];

        if ($request->has('medical_no') && $request->medical_no != '') {
            $medicalNo = $request->medical_no;
        }

        if ($medicalNo) {
            $appointments = $this->patientService->getAppointments($medicalNo);
            $newAppointments = $appointments->data->toArray();
            $tempResponse = $newAppointments;
        }

        $response = $tempResponse;

        if ($request->has('service_unit_id') && $request->service_unit_id != '') {
            $serviceUnitId = $request->service_unit_id;
            $tempResponse = [];

            foreach ($response as $appointment) {
                if ($appointment['serviceUnitID'] == $serviceUnitId) {
                    $tempResponse[] = $appointment;
                }
            }
        }

        $response = $tempResponse;

        if ($request->has('start_date') && $request->start_date != '') {
            $startDate = date('Y-m-d', strtotime($request->start_date));
            $tempResponse = [];

            foreach ($response as $appointment) {
                if (strtotime(convert_date_to_req_param($appointment['appointmentDate_yMdHms'])) >= strtotime($startDate)) {
                    $tempResponse[] = $appointment;
                }
            }
        }

        $response = $tempResponse;

        if ($request->has('end_date') && $request->end_date != '') {
            $endDate = date('Y-m-d', strtotime($request->end_date));
            $tempResponse = [];

            foreach ($response as $appointment) {

                if (strtotime(convert_date_to_req_param($appointment['appointmentDate_yMdHms'])) <= strtotime($endDate)) {
                    $tempResponse[] = $appointment;
                }
            }
        }

        $response = $tempResponse;

        if ($serviceUnitId == '' &&
            $startDate == '' &&
            $endDate == '' &&
            count($response) < 1) {
            $response = $newAppointments;
        }

        // Get local appointment
        if (count($response) == 0 && (! $medicalNo || $medicalNo == '')) {
            $localAppointments = Appointment::where('related_user_id', '=', $selectedUserId)->get();

            foreach ($localAppointments as $localAppointment) {
                $simrsAppointment = $this->patientService->getAppointment($localAppointment->appointment_no);
                $response[] = $simrsAppointment->toArray();
            }
        }

        $opens = [];
        $cancels = [];
        $dones = [];

        foreach ($response as $appointment) {
            if ($appointment['appointmentStatus'] == '01') { // open
                $opens[] = $appointment;
            } elseif ($appointment['appointmentStatus'] == '02') { // done
                $dones[] = $appointment;
            } else { // cancel
                $cancels[] = $appointment;
            }
        }

        return response()->json([
            'appointments' => [
                'dones' => $dones,
                'opens' => $opens,
                'cancels' => $cancels,
            ],
        ]);
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function store(StoreAppointmentRequest $request)
    {
        $user = User::findOrFail(auth()->user()->id);

        DB::transaction(function () use ($request, $user) {
            // register it self
            if (! $request->is_family_member) {
                $appointmentData = new CreateAppointment(
                    $request->service_unit_id,
                    $request->paramedic_id,
                    get_date_from_datetime($request->appointment_date),
                    'AUTO',
                    $user->userPatientData->patient_id ?? '',
                    $request->patient_name,
                    '',
                    '',
                    $request->birth_date,
                    $request->gender === 'Perempuan' ? 'F' : 'M',
                    '',
                    $user->email ?? '',
                    'SELF',
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

                $localAppoinment = Appointment::create([
                    'user_id' => auth()->user()->id,
                    'related_user_id' => $user->id,
                    'service_unit_id' => $appoinment->serviceUnitID,
                    'appointment_no' => $appoinment->appointmentNo,
                    'is_family_member' => false,
                    'appointment_date' => get_date_from_datetime($request->appointment_date),
                ]);

                if (! $user->userPatientData->medical_no) {
                    $user->update([
                        'medical_no' => $appoinment->medicalNo,
                    ]);
                }

                Notification::create([
                    'doctor_id' => $request->paramedic_id,
                    'context' => Notification::APPOINTMENT,
                    'message' => 'Anda mendapatkan janji temu baru',
                    'appointment_date' => $request->appointment_date,
                ]);

                return response()->json([
                    'appointment' => $localAppoinment,
                ]);

            } else { // register family member
                $family = Family::where('patient_id', '=', $request->patient_id)->first();
                if (! $family) {
                    throw new \Exception('Gagal mengambil data family member');
                }

                $appointmentData = new CreateAppointment(
                    $request->service_unit_id,
                    $request->paramedic_id,
                    get_date_from_datetime($request->appointment_date),
                    'AUTO',
                    $request->patient_id ?? '',
                    $request->patient_name,
                    '',
                    '',
                    $request->birth_date,
                    $request->gender === 'Perempuan' ? 'F' : 'M',
                    '',
                    $family->email ?? '',
                    'SELF',
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

                Notification::create([
                    'doctor_id' => $request->paramedic_id,
                    'context' => Notification::APPOINTMENT,
                    'message' => 'Anda mendapatkan janji temu baru',
                ]);

                $localAppoinment = Appointment::create([
                    'user_id' => auth()->user()->id,
                    'related_user_id' => $family->id,
                    'service_unit_id' => $appoinment->serviceUnitID,
                    'appointment_no' => $appoinment->appointmentNo,
                    'is_family_member' => true,
                    'appointment_date' => $request->appointment_date,
                ]);

                if (! $family->medical_no) {
                    $user->update([
                        'medical_no' => $appoinment->medicalNo,
                    ]);
                }

                return response()->json([
                    'appointment' => $localAppoinment,
                ]);
            }
        });
    }

    public function destroy(DestroyAppointmentRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->patientService->deleteAppointment($request->appointment_no);

        return response()->json([], 204);
    }
}
