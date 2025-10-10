<?php

namespace App\Http\Controllers\Api\V1\Patient;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\User;
use App\Services\SimrsService\PatientService\IPatientService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use stdClass;

class MedicalHistoryController extends Controller
{
    private IPatientService $patientService;

    public function __construct(IPatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    public function vitalSign(Request $request)
    {
        $response = new stdClass();
        $prevData = [];
        if ($request->has('prev_data')) {
            $prevData = $request->get('prev_data');
        }

        if ($request->has('family_member_id') && $request->family_member_id != 0) {
            $user = Family::findOrFail($request->family_member_id);
            if (! $user->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            $vitalSignHistoryData = $this->patientService
                ->getVitalSignHistory($request->type ?? '', 10, $user->medical_no);

            $response->histories = $vitalSignHistoryData->data;
            $response->patient = $user;
        } else {
            $user = User::where('id', '=', $request->user()->id)->first();
            if (! $user->userPatientData->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            $vitalSignHistoryData = $this->patientService
                ->getVitalSignHistory($request->type ?? '', 999, $user->userPatientData->medical_no);

            $response->histories = $vitalSignHistoryData->data;
            $response->patient = $user;
            $response->patient->gender = $user->userPatientData->gender;
            $response->patient->medical_no = $user->userPatientData->medical_no;
            $response->patient->birth_date = $user->userPatientData->birth_date;
        }

        $paginatedHistories = [];
        if (count($prevData) >= 10) {
            foreach ($response->histories as $patient) {
                if (! in_array($patient->registrationNo, $prevData)) {
                    $paginatedHistories[] = $patient;
                }

                if (count($paginatedHistories) == (count($prevData) + 10)) {
                    break;
                }
            }
        } else {
            foreach ($response->histories as $patient) {
                $paginatedHistories[] = $patient;
                if (count($paginatedHistories) == 10) {
                    break;
                }
            }
        }

        $response->histories = $paginatedHistories;

        return response()->json($response);
    }

    public function prescriptionHistory(Request $request)
    {
        $response = new stdClass();
        $prevData = [];
        if ($request->has('prev_data')) {
            $prevData = $request->get('prev_data');
        }
        if ($request->has('family_member_id') && $request->family_member_id != 0) {
            $user = Family::findOrFail($request->family_member_id);
            if (! $user->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            $prescriptionHistories = $this->patientService
                ->getPrescriptionHistory(10, $user->medical_no);

            $response->histories = $prescriptionHistories->data;
            $response->patient = $user;
        } else {
            $user = User::where('id', '=', $request->user()->id)->first();
            if (! $user->userPatientData->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            $prescriptionHistories = $this->patientService->getPrescriptionHistory(10, $user->userPatientData->medical_no);

            $response->histories = $prescriptionHistories->data;
            $response->patient = $user;
            $response->patient->gender = $user->userPatientData->gender;
            $response->patient->medical_no = $user->userPatientData->medical_no;
            $response->patient->birth_date = $user->userPatientData->birth_date;
        }

        $paginatedHistories = [];
        // Filter out histories where PrescriptionNo contains 'RSP'
        $filteredHistories = array_filter($response->histories->toArray(), function ($recipe) {
            return strpos($recipe['PrescriptionNo'], 'RSP') === false;
        });

        if (count($prevData) >= 10) {
            foreach ($filteredHistories as $patient) {
                if (! in_array($patient['PrescriptionNo'], $prevData)) {
                    $paginatedHistories[] = $patient;
                }

                if (count($paginatedHistories) == (count($prevData) + 10)) {
                    break;
                }
            }
        } else {
            foreach ($filteredHistories as $patient) {
                $paginatedHistories[] = $patient;
                if (count($paginatedHistories) == 10) {
                    break;
                }
            }
        }

        $response->histories = $paginatedHistories;

        return response()->json($response);
    }

    public function prescriptionHistoryDetail(Request $request)
    {
        $user = User::where('id', '=', $request->user()->id)->first();
        if (! $user->userPatientData->medical_no) {
            throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
        }

        if (! $request->has('prescription_no')) {
            throw ValidationException::withMessages(['prescription_no' => 'Gagal mengambil data']);
        }

        $prescriptionHistoryDetail = $this->patientService->getPrescriptionHistoryDetail($request->prescription_no);

        $response = new stdClass();
        $response->data = $prescriptionHistoryDetail->data;
        $response->patient = $user;
        $response->patient->patient_data = $user->userPatientData;

        return response()->json($response);
    }

    public function labResult(Request $request)
    {
        $response = new stdClass();
        $prevData = [];
        if ($request->has('prev_data')) {
            $prevData = $request->get('prev_data');
        }
        if ($request->has('family_member_id') && $request->family_member_id !== null) {
            $user = Family::findOrFail($request->family_member_id);
            if (! $user->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            $labResults = $this->patientService->getLabResult($user->medical_no);

            $response->histories = $labResults->data;
            $response->patient = $user;
        } else {
            $user = User::where('id', '=', $request->user()->id)->first();
            if (! $user->userPatientData->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            $labResults = $this->patientService->getLabResult($user->userPatientData->medical_no);

            $response->histories = $labResults->data;
            $response->patient = $user;
            $response->patient->gender = $user->userPatientData->gender;
            $response->patient->medical_no = $user->userPatientData->medical_no;
            $response->patient->birth_date = $user->userPatientData->birth_date;
        }

        $paginatedHistories = [];
        if (count($prevData) >= 10) {
            foreach ($response->histories as $patient) {
                if (! in_array($patient->registrationNo, $prevData)) {
                    $paginatedHistories[] = $patient;
                }

                if (count($paginatedHistories) == (count($prevData) + 10)) {
                    break;
                }
            }
        } else {
            foreach ($response->histories as $patient) {
                $paginatedHistories[] = $patient;
                if (count($paginatedHistories) == 10) {
                    break;
                }
            }
        }

        $response->histories = $paginatedHistories;

        return response()->json($response);
    }

    public function labResultFile(Request $request, $transactionNo)
    {
        //$transactionNo = 'DS231217-0024';
        $fileUrlLink = env('LAB_FILE_URL', '127.0.0.1') . '/' . $transactionNo . '.' . env('LAB_FILE_EXTENSION', 'pdf');
        return response()->json(['message' => 'link file created', 'data' => $fileUrlLink]);
    }

    public function labResultDetail(Request $request)
    {
        $user = User::where('id', '=', $request->user()->id)->first();
        if (! $user->userPatientData->medical_no) {
            throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
        }

        if (! $request->has('transaction_no')) {
            throw ValidationException::withMessages(['transaction_no' => 'Gagal mengambil data']);
        }

        $labResultsDetail = $this->patientService->getLabResultDetail($request->transaction_no);
        $response = new stdClass();
        $response->data = $labResultsDetail->data;
        $response->patient = $user;
        $response->patient->patient_data = $user->userPatientData;

        return response()->json($response);
    }

    public function radResult(Request $request)
    {
        $response = new stdClass();
        $prevData = [];
        if ($request->has('prev_data')) {
            $prevData = $request->get('prev_data');
        }
        if ($request->has('family_member_id') && $request->family_member_id !== null) {
            $user = Family::findOrFail($request->family_member_id);
            if (! $user->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            $labResults = $this->patientService->getRadResult($user->medical_no);

            $response->histories = $labResults->data;
            $response->patient = $user;
        } else {
            $user = User::where('id', '=', $request->user()->id)->first();
            if (! $user->userPatientData->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            $labResults = $this->patientService->getRadResult($user->userPatientData->medical_no);

            $response->histories = $labResults->data;
            $response->patient = $user;
            $response->patient->gender = $user->userPatientData->gender;
            $response->patient->medical_no = $user->userPatientData->medical_no;
            $response->patient->birth_date = $user->userPatientData->birth_date;
        }

        $paginatedHistories = [];
        if (count($prevData) >= 10) {
            foreach ($response->histories as $patient) {
                if (! in_array($patient->registrationNo, $prevData)) {
                    $paginatedHistories[] = $patient;
                }

                if (count($paginatedHistories) == (count($prevData) + 10)) {
                    break;
                }
            }
        } else {
            foreach ($response->histories as $patient) {
                $paginatedHistories[] = $patient;
                if (count($paginatedHistories) == 10) {
                    break;
                }
            }
        }

        $response->histories = $paginatedHistories;

        return response()->json($response);
    }

    public function radResultFile(Request $request, $transactionNo)
    {
        //$transactionNo = 'DS231217-0024';
        $fileUrlLink = env('LAB_FILE_URL', '127.0.0.1') . '/' . $transactionNo . '.' . env('LAB_FILE_EXTENSION', 'pdf');
        return response()->json(['message' => 'link file created', 'data' => $fileUrlLink]);
    }

    public function radResultDetail(Request $request)
    {
        $user = User::where('id', '=', $request->user()->id)->first();
        if (! $user->userPatientData->medical_no) {
            throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
        }

        if (! $request->has('transaction_no')) {
            throw ValidationException::withMessages(['transaction_no' => 'Gagal mengambil data']);
        }

        $labResultsDetail = $this->patientService->getRadResultDetail($request->transaction_no);
        $response = new stdClass();
        $response->data = $labResultsDetail->data;
        $response->patient = $user;
        $response->patient->patient_data = $user->userPatientData;

        return response()->json($response);
    }

    public function encounterList(Request $request)
    {
        $response = new stdClass();
        $prevData = [];
        if ($request->has('prev_data')) {
            $prevData = $request->get('prev_data');
            $previousYear = date('Y') - 1;
            $currentMonth = date('m');
            $startDate = $previousYear . '-' . $currentMonth . '-01';
            $endDate = date('Y-m-d');
        } else {
            $previousYear = date('Y') - 1;
            $currentMonth = date('m');
            $startDate = $previousYear . '-' . $currentMonth . '-01';
            $endDate = date('Y-m-d');
        }

        // return $prevData;

        if ($request->has('family_member_id') && $request->family_member_id != 0) {
            $user = Family::findOrFail($request->family_member_id);
            if (! $user->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            // return response()->json(['start_date' => $startDate, 'end_date' => $endDate, 'medical_no' => $user->medical_no]);
            $encountersList = $this->patientService->getEncounterList(
                $user->medical_no,
                '',
                '',
                $startDate, //get_current_year_start_date(),
                $endDate, //get_current_month_date(),
            );

            $response->histories = $encountersList->data;
            $response->patient = $user;
        } else {
            $user = User::where('id', '=', $request->user()->id)->first();
            if (! $user->userPatientData->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            $encountersList = $this->patientService->getEncounterList(
                $user->userPatientData->medical_no,
                '',
                '',
                $startDate, //get_current_year_start_date(),
                $endDate, //get_current_month_date(),
            );

            // return $encountersList;
            $response->histories = $encountersList->data;
            $response->patient = $user;
            $response->patient->gender = $user->userPatientData->gender;
            $response->patient->medical_no = $user->userPatientData->medical_no;
            $response->patient->birth_date = $user->userPatientData->birth_date;
        }

        $paginatedHistories = [];
        if (count($prevData) >= 10) {
            foreach ($response->histories as $patient) {
                if (! in_array($patient->registrationNo, $prevData)) {
                    $paginatedHistories[] = $patient;
                }

                if (count($paginatedHistories) == (count($prevData) + 10)) {
                    break;
                }
            }
        } else {
            foreach ($response->histories as $patient) {
                $paginatedHistories[] = $patient;
                if (count($paginatedHistories) == 10) {
                    break;
                }
            }
        }

        $response->histories = $paginatedHistories;

        return response()->json($response);
    }

    public function encounterListDetail(Request $request)
    {
        $user = User::where('id', '=', $request->user()->id)->first();
        if (! $user->userPatientData->medical_no) {
            throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
        }

        if (! $request->has('registration_no')) {
            throw ValidationException::withMessages(['registration_no' => 'Gagal mengambil data']);
        }

        $encounterDetails = $this->patientService->getEncounterListDetail(
            $request->registration_no,
            '',
            ''
        );

        $response = new stdClass();
        $response->details = $encounterDetails->data;
        $response->patient = $user;
        $response->patient->patient_data = $user->userPatientData;

        return response()->json($response);
    }
}
