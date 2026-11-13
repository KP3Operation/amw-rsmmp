<?php

namespace App\Http\Controllers\Api\V1\Patient;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\User;
use App\Services\SimrsService\PatientService\IPatientService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use stdClass;

class MedicalHistoryController extends Controller
{
    /**
     * Jumlah baris yang diminta dari SIMRS untuk satu tab riwayat.
     *
     * Halaman riwayat memuat satu jendela data sekali saja lalu memecahnya
     * menjadi halaman-halaman di sisi klien, jadi angka ini sekaligus menjadi
     * batas atas data yang bisa ditelusuri pengguna. 50 adalah ukuran terbesar
     * yang masih dijawab VitalSignByMedicalNo (~23 detik) sebelum menabrak
     * timeout 30 detik miliknya sendiri; endpoint riwayat lain jauh lebih cepat
     * tetapi disamakan supaya perilaku tiap tab seragam.
     */
    private const HISTORY_RECORD_COUNT = 50;

    private IPatientService $patientService;

    public function __construct(IPatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    public function vitalSign(Request $request)
    {
        $response = new stdClass();
        if ($request->filled('family_member_id') && $request->family_member_id != 0) {
            $user = Family::findOrFail($request->family_member_id);
            if (! $user->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            $vitalSignHistoryData = $this->patientService
                ->getVitalSignHistory($request->type ?? '', self::HISTORY_RECORD_COUNT, $user->medical_no);

            $response->histories = $vitalSignHistoryData->data;
            $response->patient = $user;
        } else {
            $user = User::where('id', '=', $request->user()->id)->first();
            if (! $user->userPatientData->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            $vitalSignHistoryData = $this->patientService
                ->getVitalSignHistory($request->type ?? '', self::HISTORY_RECORD_COUNT, $user->userPatientData->medical_no);

            $response->histories = $vitalSignHistoryData->data;
            $response->patient = $user;
            $response->patient->gender = $user->userPatientData->gender;
            $response->patient->medical_no = $user->userPatientData->medical_no;
            $response->patient->birth_date = $user->userPatientData->birth_date;
        }

        // Seluruh jendela data dikirim sekali; pemecahan per halaman dikerjakan
        // di sisi klien, jadi berpindah halaman tidak memanggil SIMRS lagi.
        $histories = [];
        foreach ($response->histories as $history) {
            $histories[] = $history;
        }

        $response->histories = $histories;

        return response()->json($response);
    }

    public function prescriptionHistory(Request $request)
    {
        $response = new stdClass();
        if ($request->filled('family_member_id') && $request->family_member_id != 0) {
            $user = Family::findOrFail($request->family_member_id);
            if (! $user->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            $prescriptionHistories = $this->patientService
                ->getPrescriptionHistory(self::HISTORY_RECORD_COUNT, $user->medical_no);

            $response->histories = $prescriptionHistories->data;
            $response->patient = $user;
        } else {
            $user = User::where('id', '=', $request->user()->id)->first();

            if (! $user->userPatientData->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            $prescriptionHistories = $this->patientService->getPrescriptionHistory(self::HISTORY_RECORD_COUNT, $user->userPatientData->medical_no);
            $response->histories = $prescriptionHistories->data;
            $response->patient = $user;
            $response->patient->gender = $user->userPatientData->gender;
            $response->patient->medical_no = $user->userPatientData->medical_no;
            $response->patient->birth_date = $user->userPatientData->birth_date;
        }

        // Filter out histories where PrescriptionNo contains 'RSP'
        $historiesArray = is_array($response->histories) ? $response->histories : $response->histories->toArray();
        $filteredHistories = array_filter($historiesArray, function ($recipe) {
            return Str::contains($recipe['PrescriptionNo'], 'RSP') === true;
        });

        // Seluruh jendela data dikirim sekali; pemecahan per halaman dikerjakan
        // di sisi klien, jadi berpindah halaman tidak memanggil SIMRS lagi.
        $response->histories = array_values($filteredHistories);

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
        if ($request->filled('family_member_id')) {
            $user = Family::findOrFail($request->family_member_id);
            if (! $user->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            $labResults = $this->patientService->getLabResult($user->medical_no, self::HISTORY_RECORD_COUNT);

            $response->histories = $labResults->data;
            $response->patient = $user;
        } else {
            $user = User::where('id', '=', $request->user()->id)->first();
            if (! $user->userPatientData->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            $labResults = $this->patientService->getLabResult($user->userPatientData->medical_no, self::HISTORY_RECORD_COUNT);

            $response->histories = $labResults->data;
            $response->patient = $user;
            $response->patient->gender = $user->userPatientData->gender;
            $response->patient->medical_no = $user->userPatientData->medical_no;
            $response->patient->birth_date = $user->userPatientData->birth_date;
        }

        // Seluruh jendela data dikirim sekali; pemecahan per halaman dikerjakan
        // di sisi klien, jadi berpindah halaman tidak memanggil SIMRS lagi.
        $histories = [];
        foreach ($response->histories as $history) {
            $histories[] = $history;
        }

        $response->histories = $histories;

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
        if ($request->filled('family_member_id')) {
            $user = Family::findOrFail($request->family_member_id);
            if (! $user->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            $labResults = $this->patientService->getRadResult($user->medical_no, self::HISTORY_RECORD_COUNT);

            $response->histories = $labResults->data;
            $response->patient = $user;
        } else {
            $user = User::where('id', '=', $request->user()->id)->first();
            if (! $user->userPatientData->medical_no) {
                throw new ModelNotFoundException("Tidak ada No. RM untuk pasien {$user->name}");
            }

            $labResults = $this->patientService->getRadResult($user->userPatientData->medical_no, self::HISTORY_RECORD_COUNT);

            $response->histories = $labResults->data;
            $response->patient = $user;
            $response->patient->gender = $user->userPatientData->gender;
            $response->patient->medical_no = $user->userPatientData->medical_no;
            $response->patient->birth_date = $user->userPatientData->birth_date;
        }

        // Seluruh jendela data dikirim sekali; pemecahan per halaman dikerjakan
        // di sisi klien, jadi berpindah halaman tidak memanggil SIMRS lagi.
        $histories = [];
        foreach ($response->histories as $history) {
            $histories[] = $history;
        }

        $response->histories = $histories;

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

        // Rentang tetap: dari bulan yang sama tahun lalu sampai hari ini.
        $previousYear = date('Y') - 1;
        $currentMonth = date('m');
        $startDate = $previousYear . '-' . $currentMonth . '-01';
        $endDate = date('Y-m-d');

        if ($request->filled('family_member_id') && $request->family_member_id != 0) {
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

        // Seluruh jendela data dikirim sekali; pemecahan per halaman dikerjakan
        // di sisi klien, jadi berpindah halaman tidak memanggil SIMRS lagi.
        $histories = [];
        foreach ($response->histories as $history) {
            $histories[] = $history;
        }

        $response->histories = $histories;

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
