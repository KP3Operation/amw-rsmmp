<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthenticateRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\AuthenticateResource;
use App\Models\OtpCode;
use App\Models\Role;
use App\Models\User;
use App\Services\OtpService\OtpWrapper\IOtpWrapperService;
use App\Services\SimrsService\PatientService\IPatientService;
use Auth;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    private IOtpWrapperService $otpService;
    private IPatientService $patientService;

    public function __construct(IOtpWrapperService $otpService, IPatientService $patientService)
    {
        $this->otpService = $otpService;
        $this->patientService = $patientService;
    }

    public function sendOtp(LoginRequest $loginRequest): LoginResource
    {
        $user = User::where('phone_number', '=', $loginRequest->validated('phone_number'))->first();

        if (!$user)
            throw new ModelNotFoundException(__("login.errros.wrong_phone_number"));

        $otpCode = $this->otpService->sendOtp($user);

        $user->otp_created_at = $otpCode->created_at;
        $user->otp_updated_at = $otpCode->updated_at;
        $user->otp_timeout = 30000; // miliseconds - 10 seconds

        return new LoginResource($user);
    }

    public function authenticate(AuthenticateRequest $authenticateRequest): AuthenticateResource
    {
        $userOtpCode = OtpCode::whereCode($authenticateRequest->validated('code'))->whereStatus('unverified')->first();
        if (!$userOtpCode)
            throw ValidationException::withMessages(["code" => __("login.errros.wrong_otp_code")]);

        if (date_diff_in_second($userOtpCode->updated_at) > config('app.otp_expired_in'))
            throw ValidationException::withMessages(["code" => __("login.errros.otp_expired")]);

        $user = User::find($userOtpCode->user_id);
        if (!$user)
            throw new Exception(__("unhandled error"), 500);

        $userOtpCode->update([
            'status' => 'verified'
        ]);

        Auth::loginUsingId($user->id);
        $authenticateRequest->session()->regenerate();

        // delete old tokens first
        $user->tokens()->delete();

        // NOTE: Not sure, but look like we don't need to generate token, as the authorization id done via cookies
        // $user->token = $user->createToken('auth_token')->plainTextToken;
        foreach ($user->roles as $role) {
            $user->role = $role->name;
            break;
        }

        if (user_role($user->id) == Role::PATIENT) {
            $patientData = $this->patientService->getPatients($user)->data->first();
            $user->patient_data = $patientData;
        }

        return new AuthenticateResource($user);
    }
}
