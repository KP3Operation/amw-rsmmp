<?php

namespace App\Http\Controllers\Api\V1\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\UpdatePatientRequest;
use App\Http\Resources\Patient\UpdatePatientResource;
use App\Models\User;
use App\Models\UserDoctor;
use App\Models\UserPatient;
use App\Services\SimrsService\PatientService\IPatientService;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class MeController extends Controller
{
    private IPatientService $patientService;

    public function __construct(IPatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    public function index(Request $request)
    {
        $currentRole = user_role($request->user()->id);
        if ($currentRole == "patient") {
            $userPatient = UserPatient::where('user_id', '=', $request->user()->id)->first();
            return response()->json([
                "user" => $request->user(),
                "patient_data" => $userPatient,
                "role" => $currentRole
            ]);
        }

        $userDoctor = UserDoctor::where('user_id', '=', $request->user()->id)->first();
        return response()->json([
            "user" => $request->user(),
            "doctor_data" => $userDoctor,
            "role" => $currentRole
        ]);
    }

    public function syncData(Request $request)
    {
        $user = User::where('id', '=', $request->user()->id)->first();

        $response = $this->patientService->getPatients($user);

        DB::transaction(function () use ($response, $user) {
            $patientData = $response->data->first();
            $user->update([
                "name" => $patientData->firstName . " " . $patientData->middleName . " " . $patientData->lastName,
                "email" => $patientData->email
            ]);

            if ($user->userPatientData) {
                $user->userPatientData()->update([
                    "patient_id" => $patientData->patientId,
                    "ssn" => $patientData->ssn,
                    "birth_date" => $patientData->birthDate,
                    "gender" => $patientData->gender == "F" ? "Perempuan" : "Laki-Laki",
                    "sync_at" => Carbon::now()
                ]);
            } else {
                UserPatient::create([
                    "user_id" => $user->id,
                    "patient_id" => $patientData->patientId,
                    "ssn" => $patientData->ssn,
                    "birth_date" => $patientData->birthDate,
                    "gender" => $patientData->gender == "F" ? "Perempuan" : "Laki-Laki",
                    "sync_at" => Carbon::now()
                ]);
            }
        });

        return response()->json([], 204);
    }

    public function update(UpdatePatientRequest $request): UpdatePatientResource
    {
        $user = User::where('id', '=', $request->user()->id)->first();
        $user->update($request->only('name', 'email'));
        if ($user->userPatientData) {
            $user->userPatientData()->update($request->only('birth_date', 'gender'));
        }

        return new UpdatePatientResource($user);
    }
}
