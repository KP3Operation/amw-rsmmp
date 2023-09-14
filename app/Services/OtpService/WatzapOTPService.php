<?php

namespace App\Services\OtpService;

use App\Models\OtpCode;
use App\Jobs\SendWatzapOtp;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class WatzapOTPService implements IOTPService
{

    public function sendOtp(User $user): OtpCode
    {
        $code = $this->generateOTP(6);

        if (config('simrs.otp_with_queue')) {
            SendWatzapOtp::dispatch($user->phone_number, $code);
        } else {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->withOptions([
                "verify" => false
            ])->post(config('watzap.send_message_url'), [
                'api_key' => config('watzap.api_key'),
                'number_key' => config('watzap.number_key'),
                'phone_no' => str_replace("+", "", $user->phone_number),
                'message' => __("login.otp_message", ["otpcode" => $code])
            ]);

            if ($response->ok()) {
                $status = $response->collect('status')->first();
                $message = $response->collect('message')->first();
                if ($status == "200" || $message == "Successfully") {
                    // We are good
                } else if ($status >= 400 && $status <= 499) {
                    // Something wrong with payloads or authentication
                    throw new \Exception("There is an error while communicating with watzap service", 400);
                } else if ($status >= 500 && $status <= 599) {
                    // watzap server error
                    throw new \Exception("There is an error while communicating with watzap service", 500);
                } else {
                    // Don't know why it is error :)
                    throw new \Exception("Internal server error", 500);
                }
            } else {
                throw new \Exception("Internal server error", 500);
            }
        }

        $otpCode = OtpCode::whereUserId($user->id)->first();
        if ($otpCode != null) {
            $otpCode->update([
                'code' => $code,
                'status' => 'unverified'
            ]);
        } else {
            $otpCode = OtpCode::create([
                'user_id' => $user->id,
                'code' => $code,
                'status' => 'unverified'
            ]);
        }

        return $otpCode;
    }

    public function getStatus(): bool
    {
        throw new \Exception("Unimplemented");
    }

    private function generateOTP(int $length): int
    {
        $otp = '0';
        $characters = '0123456789'; // possible characters for the OTP

        $charCount = strlen($characters);
        while (strlen($otp) <= $length) {
            $otp .= $characters[rand(1, $charCount - 1)];
        }

        $otp = (int) $otp;

        $existingCode = OtpCode::where('code', '=', $otp)->first();

        if ($otp == 0 || $existingCode != null)
            $this->generateOTP($length);

        return $otp;
    }
}
