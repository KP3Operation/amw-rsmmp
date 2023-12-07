<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Exceptions\RestApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthenticateRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\OtpCode;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDoctor;
use App\Services\OtpService\OtpWrapper\IOtpWrapperService;
use App\Services\SimrsService\PatientService\IPatientService;

class LoginController extends Controller
{
    private IOtpWrapperService $otpService;
    private IPatientService $patientService;

    public function __construct(IOtpWrapperService $otpService, IPatientService $patientService)
    {
        $this->otpService = $otpService;
        $this->patientService = $patientService;
    }

    /**
     * @throws RestApiException
     */
    public function sendOtp(LoginRequest $loginRequest)
    {
        $user = User::where('phone_number', '=', $loginRequest
            ->validated('phoneNumber'))
            ->first();

        if (!$user) {
            throw new RestApiException("No. Handphone tidak terdaftar", 404);
        }

        $otpCode = $this->otpService->sendOtp($user);

        $resource = [];
        $resource['otpCreatedAt'] = $otpCode->created_at;
        $resource['otpUpdatedAt'] = $otpCode->updated_at;
        $resource['otpTimeout'] = 30000; // miliseconds ; 30 seconds
        $resource['phoneNumber'] = $user->phone_number;

        return response()->json($resource);
    }

    public function authenticate(AuthenticateRequest $authenticateRequest)
    {
        $userOtpCode = OtpCode::whereCode($authenticateRequest->validated('code'))->whereStatus('unverified')->first();
        if (!$userOtpCode) {
            throw new RestApiException("Kode OTP salah", 404);
        }

        if (date_diff_in_second($userOtpCode->updated_at) > config('app.otp_expired_in')) {
            throw new RestApiException("Kode OTP telah kedaluwarsa", 400);
        }

        $user = User::find($userOtpCode->user_id);
        if (!$user) {
            throw new RestApiException('Terjadi kesalahan saat membaca data. Mohon untuk login kembali', 500);
        }

        // NOTE: Not sure, but look like we don't need to generate token, as the authorization id done via cookies
        // $user->token = $user->createToken('auth_token')->plainTextToken;
        foreach ($user->roles as $role) {
            $user->role = $role->name;
            break;
        }

        $resource = [];
        $resource['user']['id'] = $user->id;
        $resource['user']['name'] = $user->name;
        $resource['user']['phoneNumber'] = $user->phone_number;

        if (user_role_id($user->id) == Role::PATIENT) {

            $patientData = $this->patientService->getPatients($user->phone_number, $user->userPatientData->ssn)->data->first();

            $resource['userPatient']['patientId'] = $patientData->patientId ?? $user->userPatientData->patient_id ?? "";
            $resource['userPatient']['medicalNo'] = $patientData->medicalNo ?? $user->userPatientData->medical_no ?? "";
            $resource['userPatient']['gender'] = $patientData->gender ?? $user->userPatientData->gender ?? "";
            $resource['userPatient']['birthDate'] = $patientData->birthDate ?? $user->userPatientData->birth_date ?? "";
            $resource['userPatient']['ssn'] = $patientData->ssn ?? $user->userPatientData->ssn ?? "";
            $resource['userPatient']['userEmail'] = $user->email;
        } else {
            $userDoctorData = UserDoctor::where('user_id', '=', $user->id)->first();
            $resource['userDoctor']['doctorId'] = $userDoctorData->doctor_id;
            $resource['userDoctor']['smfName'] = $userDoctorData->smf_name;
        }

        $userOtpCode->update([
            'status' => 'verified'
        ]);

        \Auth::loginUsingId($user->id);
        $authenticateRequest->session()->regenerate();

        // delete old tokens first
        $user->tokens()->delete();

        return response()->json($resource);
    }
}
