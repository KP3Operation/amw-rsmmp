<?php

namespace App\Services\OtpService\OtpWrapper;

use App\Exceptions\RestApiException;
use App\Jobs\SendWatzapOtp;
use App\Models\OtpCode;
use App\Models\User;
use App\Services\OtpService\Watzap\IWatzapOtpService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OtpWrapperService implements IotpWrapperService
{
    private IWatzapOtpService $otpService;

    public function __construct(IWatzapOtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function sendOtp(User $user): OtpCode
    {

        if (config("watzap.validate_whatsapp_number")) {
            if (!$this->otpService->isPhoneNumberValid($user)) {
                Log::error("Phone number {phoneNumber} is not valid", ["phoneNumber" => $user->phone_number]);
                $user->delete();
                throw new RestApiException('No. Handphone tidak terdaftar di WhatsApp', 500);
            }
        }

        // NOTE: To check api key status
        $this->otpService->isApiKeyValid();

        $code = generate_otp(6);


        // DEV: Hardcoded OTP for dev only
        if (config('app.env') == 'local') {
            $code = 12345;
        }

        if (config("app.otp_with_queue")) {

            // NOTE: If otp is sended by queue then we can's intercept the errors
            //       we can intercept the error in the queue job
            SendWatzapOtp::dispatch($user->phone_number, $code);
        } else {
            $this->otpService->sendOtp($user, $code);
        }

        $oldOtpCodes = OtpCode::where('user_id', '=', $user->id)->get();

        foreach ($oldOtpCodes as $oldOtpCode) {
            $oldOtpCode->delete();
        }

        $otpCode = OtpCode::where('code', '=', $code)->first();
        if ($otpCode) {
            $otpCode->delete();

            return OtpCode::create([
                'user_id' => $user->id,
                'code' => $code,
                'status' => 'unverified',
                'message_id' => null,
                'updated_at' => Carbon::now()
            ]);
        }

        return OtpCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'status' => 'unverified',
            'message_id' => null,
            'updated_at' => Carbon::now()
        ]);
    }

    public function sendRegistrationOtp(User $user)
    {


        if (config("watzap.validate_whatsapp_number")) {
            if (!$this->otpService->isPhoneNumberValid($user)) {
                Log::error("Phone number {phoneNumber} is not valid", ["phoneNumber" => $user->phone_number]);
                $user->delete();
                throw new RestApiException('No. Handphone tidak terdaftar di WhatsApp', 500);
            }
        }

        // NOTE: To check api key status
        $this->otpService->isApiKeyValid();

        $code = generate_otp(6);


        // DEV: Hardcoded OTP for dev only
        if (config('app.env') == 'local') {
            $code = 12345;
        }

        if (config("app.otp_with_queue")) {

            // NOTE: If otp is sended by queue then we can's intercept the errors
            //       we can intercept the error in the queue job
            SendWatzapOtp::dispatch($user->phone_number, $code);
        } else {
            $this->otpService->sendRegistrationOtp($user, $code);
        }

        $oldOtpCodes = OtpCode::where('user_id', '=', $user->id)->get();

        foreach ($oldOtpCodes as $oldOtpCode) {
            $oldOtpCode->delete();
        }

        $otpCode = OtpCode::where('code', '=', $code)->first();
        if ($otpCode) {
            $otpCode->delete();

            return OtpCode::create([
                'user_id' => $user->id,
                'code' => $code,
                'status' => 'unverified',
                'message_id' => null,
                'updated_at' => Carbon::now()
            ]);
        }

        return OtpCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'status' => 'unverified',
            'message_id' => null,
            'updated_at' => Carbon::now()
        ]);
    }
}
