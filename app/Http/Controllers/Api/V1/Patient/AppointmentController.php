<?php

namespace App\Http\Controllers\Api\V1\Patient;

use App\Dto\SimrsDto\Patient\AppointmentDataDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\DestroyAppointmentRequest;
use App\Http\Requests\Patient\GetAppointmentsRequest;
use App\Http\Requests\Patient\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Family;
use App\Models\Notification;
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
        $serviceUnitId = '';
        $startDate = '';
        $endDate = '';
        $response = [];

        if ($request->has('medical_no') && $request->medical_no != '') {
            $medicalNo = $request->medical_no;
        }

        if (!$medicalNo || $medicalNo === '') {
            throw new \Exception("Pasien tidak memiliki No. RM");
        }

        $appointments = $this->patientService->getAppointments($medicalNo);

        $newAppointments = $appointments->data->toArray();

        if ($request->has('service_unit_id') && $request->service_unit_id != '') {
            $serviceUnitId = $request->service_unit_id;

            foreach ($newAppointments as $appointment) {
                if ($appointment['serviceUnitID'] == $serviceUnitId) {
                    if (!in_array($appointment, $response)) {
                        $response[] = $appointment;
                    }
                }
            }
        }

        if ($request->has('start_date') && $request->start_date != '') {
            $startDate = date('Y-m-d', strtotime($request->start_date));

            foreach ($newAppointments as $appointment) {
                if (convert_date_to_req_param($appointment['appointmentDate_yMdHms']) >= $startDate) {
                    if (!in_array($appointment, $response)) {
                        $response[] = $appointment;
                    }
                }
            }
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $endDate = date('Y-m-d', strtotime($request->end_date));

            foreach ($newAppointments as $appointment) {
                if (convert_date_to_req_param($appointment['appointmentDate_yMdHms']) <= $endDate) {
                    if (!in_array($appointment, $response)) {
                        $response[] = $appointment;
                    }
                }
            }
        }

        if ($serviceUnitId == '' &&
            $startDate == '' &&
            $endDate == '' &&
            count($response) < 1) {
            $response = $newAppointments;
        }

        $opens = [];
        $cancels = [];
        $dones = [];

        foreach ($response as $appointment) {
            if ($appointment['appointmentStatus'] == '01') { // open
                $opens[] = $appointment;
            } else if ($appointment['appointmentStatus'] == '02') { // done
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

    /**
     * @throws \Exception
     */
    public function store(StoreAppointmentRequest $request)
    {
        $user = User::findOrFail(auth()->user()->id);

        // TODO: need to use db transaction
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

            $localAppoinment = Appointment::create([
                'user_id' => auth()->user()->id,
                'related_user_id' => $user->id,
                'service_unit_id' => $appoinment->serviceUnitID,
                'appointment_no' => $appoinment->appointmentNo,
                'is_family_member' => true,
                'appointment_date' => $request->appointment_date
            ]);

            Notification::create([
                'doctor_id' => $request->paramedic_id,
                'context' => Notification::APPOINTMENT,
                'message' => 'Anda mendapatkan janji temu baru'
            ]);

            return response()->json([
                'appointment' => $localAppoinment
            ]);


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

            Notification::create([
                'doctor_id' => $request->paramedic_id,
                'context' => Notification::APPOINTMENT,
                'message' => 'Anda mendapatkan janji temu baru'
            ]);

            $localAppoinment = Appointment::create([
                'user_id' => auth()->user()->id,
                'related_user_id' => $family->id,
                'service_unit_id' => $appoinment->serviceUnitID,
                'appointment_no' => $appoinment->appointmentNo,
                'is_family_member' => true,
                'appointment_date' => $request->appointment_date
            ]);

            return response()->json([
                'appointment' => $localAppoinment
            ]);
        }
    }

    public function destroy(DestroyAppointmentRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->patientService->deleteAppointment($request->appointment_no);

        return response()->json([], 204);
    }
}
