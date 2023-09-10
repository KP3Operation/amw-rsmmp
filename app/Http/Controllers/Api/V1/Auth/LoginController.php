<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthenticateRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\AuthenticateResource;
use App\Models\OtpCode;
use App\Models\User;
use App\Services\OtpService\IOTPService;
use Auth;
use Exception;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    private IOTPService $otpService;

    public function __construct(IOTPService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function sendOtp(LoginRequest $loginRequest): LoginResource
    {
        $user = User::where('phone_number', '=', $loginRequest->validated('phone_number'))->first();

        if (!$user)
            throw ValidationException::withMessages(["phone_number" => __("login.errros.wrong_phone_number")]);

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

        $user->token = $user->createToken('auth_token')->plainTextToken;

        return new AuthenticateResource($user);
    }
}
