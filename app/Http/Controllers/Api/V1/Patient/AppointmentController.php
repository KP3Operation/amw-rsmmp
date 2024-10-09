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
use App\Models\UserPatient;
use App\Services\SimrsService\PatientService\IPatientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    private IPatientService $patientService;

    public function __construct(IPatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    public function index(Request $request)
    {
        //return response()->json(['message' => 'disini bro']);

        $user = User::findOrFail(auth()->user()->id);
        $medicalNo = $user->userPatientData->medical_no;
        $serviceUnitId = '';
        $startDate = '';
        $endDate = '';
        $response = [];
        $tempResponse = [];
        $selectedUserId = $user->id;
        $newAppointments = [];
        $selectedPatient = [
            'name' => $user->name,
            'gender' => $user->userPatientData->gender,
            'medical_no' => $user->userPatientData->medical_no,
            'birth_date' => $user->userPatientData->birth_date,
        ];

        if ($request->has('medical_no') && $request->medical_no !== '' && $request->medical_no !== null) {
            // if medical_no exists in request then it is family data
            $medicalNo = $request->medical_no;
            if ($user->userPatientData->medical_no != $medicalNo) {
                $family = Family::where('medical_no', '=', $medicalNo)->first();
                $selectedUserId = $family->id;
                $selectedPatient = [
                    'name' => $family->name,
                    'gender' => $family->gender,
                    'medical_no' => $family->medical_no,
                    'birth_date' => $family->birth_date,
                ];
            }
        }

        if ($request->has('family_id') && $request->family_id !== '' && $request->family_id !== null) {
            // if family_id exists in request then use family_id
            $familyId = $request->family_id;
            if ($user->id !== $familyId) {
                $family = Family::where('id', '=', $familyId)->first();
                $medicalNo = $family->medical_no;
                $selectedUserId = $family->id;
                $selectedPatient = [
                    'name' => $family->name,
                    'gender' => $family->gender,
                    'medical_no' => $family->medical_no,
                    'birth_date' => $family->birth_date,
                ];
            }
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

        if (
            $serviceUnitId == '' &&
            $startDate == '' &&
            $endDate == '' &&
            count($response) < 1
        ) {
            $response = $newAppointments;
        }

        // Get local appointment
        if (count($response) == 0 && (!$medicalNo || $medicalNo == '')) {
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
            if ($appointment['appointmentStatus'] == '01' || $appointment['appointmentStatus'] == '02' ) { // open
                $opens[] = $appointment;
            } elseif ($appointment['appointmentStatus'] == '04') { // done
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
            'patient' => $selectedPatient
        ]);
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function store(StoreAppointmentRequest $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $patient = UserPatient::where('user_id', auth()->user()->id)->firstOrFail();

        DB::transaction(function () use ($request, $user, $patient) {
            // register it self
            if (!$request->is_family_member) {
                $appointmentData = new CreateAppointment(
                    $request->service_unit_id, //unit ID
                    $request->paramedic_id, //paramedic ID
                    get_date_from_datetime($request->appointment_date), //appointment Date
                    'AUTO', //Appointment Time
                    $user->userPatientData->patient_id ?? '', //patient ID
                    $request->patient_name, //firstname
                    '', //middle name
                    '', //last name
                    $request->birth_date, //date of birth
                    $request->gender === 'Perempuan' ? 'F' : 'M', //$sex
                    '', //street name
                    $user->email ?? '', //email
                    'SELF', //guarantor ID
                    '', //district
                    '', //county
                    '', //city
                    '', //state
                    '', //zipcode
                    '', //phone no
                    '', //notes
                    '', //birthplace
                    $patient->ssn ?? '', //ssn
                    $user->phone_number ? $user->phone_number : '', //mobile phone no
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

                if (!$user->userPatientData->medical_no) {
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
                if ($request->has('family_id') && ($request->family_id != '' && $request->patient_id == '')) {
                    $family = Family::where('id', '=', $request->family_id)->first();
                }

                if (!$family) {
                    throw new \Exception('Gagal mengambil data family member');
                }

                $patientId = $request->patient_id;
                if ($patientId == '' || $patientId == null) {
                    $patientId = $family->patient_id;
                }

                $appointmentData = new CreateAppointment(
                    $request->service_unit_id, //unitID
                    $request->paramedic_id, //paramedicID
                    get_date_from_datetime($request->appointment_date), //appointmentDate
                    'AUTO', //appointment time
                    $patientId ?? '', //patientID
                    $request->patient_name, //firstname
                    '', //middlename
                    '', //lastname
                    $request->birth_date, //birthdate
                    $request->gender === 'Perempuan' ? 'F' : 'M', //sex
                    '', //streetname
                    $family->email ?? '', //email
                    'SELF', //guarantorID
                    '', //district
                    '', //county
                    '', //city
                    '', //state
                    '', //zipcode
                    '', //phone no
                    '', //notes
                    '', //birthplace
                    $family->ssn ?? '', //ssn
                    $family->phone_number ?? '', //mobile phone no
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

                if (!$family->medical_no) {
                    $family->update([
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
