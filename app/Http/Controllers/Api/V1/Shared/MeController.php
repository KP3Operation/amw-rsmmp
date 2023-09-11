<?php

namespace App\Http\Controllers\Api\V1\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\UpdatePatientRequest;
use App\Http\Resources\Patient\UpdatePatientResource;
use App\Models\User;
use App\Models\UserDoctor;
use App\Models\UserPatient;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use stdClass;

class MeController extends Controller
{
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
        } else if ($currentRole == "doctor") {
            $userDoctor = UserDoctor::where('user_id', '=', $request->user()->id)->first();
            return response()->json([
                "user" => $request->user(),
                "doctor_data" => $userDoctor,
                "role" => $currentRole
            ]);
        }
    }

    public function syncData(Request $request)
    {
        $user = User::where('id', '=', $request->user()->id)->first();
        $accessKey = config("simrs.access_key");
        $phoneNumber = str_replace(config('app.calling_code'), "0", $user->phone_number);
        $response = Http::withHeaders([
            'Content-Type' => "",
            "Authorization" => "Bearer " . config("simrs.bearer_token")
        ])->get(config("simrs.base_url") . "/V1_1/AppointmentWS.asmx/PatientSearchByField", [
            "AccessKey" => $accessKey,
            "MedicalNo" => "",
            "Name" => "",
            "DateOfBirth" => "",
            "Address" => "",
            "PhoneNo" => $phoneNumber,
            "Ssn" => $user->userPatientData == null ? "" : $user->userPatientData->ssn,
            "Email" => ""
        ]);

        if ($response->ok()) {
            $status = $response->collect('status')->first();
            if ($status == "OK") {
                // We are good
                // TODO: use DTO's (?)

                DB::transaction(function () use ($response, $user) {

                    $patientData = new stdClass();
                    $patientData->patient_id = $response->collect('data')[0]["PatientID"];
                    $patientData->first_name = $response->collect('data')[0]["FirstName"];
                    $patientData->birth_date = $response->collect('data')[0]["DateOfBirth"];
                    $patientData->email = $response->collect('data')[0]["Email"];
                    $patientData->ssn = $response->collect('data')[0]["Ssn"];
                    $patientData->gender = $response->collect('data')[0]["Sex"];

                    $user->update([
                        "name" => $patientData->first_name,
                        "email" => $patientData->email
                    ]);

                    // TODO: Need to parse date
                    if ($user->userPatientData) {
                        $user->userPatientData()->update([
                            "patient_id" => $patientData->patient_id,
                            "ssn" => $patientData->ssn,
                            "gender" => $patientData->gender == "F" ? "Perempuan" : "Laki-Laki"
                        ]);
                    } else {
                        UserPatient::create([
                            "user_id" => $user->id,
                            "patient_id" => $patientData->patient_id,
                            "ssn" => $patientData->ssn,
                            "gender" => $patientData->gender == "F" ? "Perempuan" : "Laki-Laki"
                        ]);
                    }
                });
                return response()->json([], 204);
            } else {
                // Don't know why it is error :)
                throw new \Exception("Internal server error", 500);
            }
        } else {
            throw new \Exception("Internal server error", 500);
        }
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
