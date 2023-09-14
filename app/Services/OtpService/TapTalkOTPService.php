<?php

namespace App\Services\OtpService;

use App\Models\OtpCode;
use App\Models\User;
use Exception;
use App\Jobs\SendTapTalkOtp;

class TapTalkOTPService implements IOTPService
{
    public function sendOtp(User $user): OtpCode
    {
        $code = $this->generateOTP(6);
        $existingCode = OtpCode::whereCode($code)->first();
        if ($existingCode != null)
            $this->sendOtp($user); // FIXME: Possible infinite looping

        if (config('simrs.otp_with_queue')) {
            SendTapTalkOtp::dispatch($code, $user->phone_number);
        } else {
            $response = Http::withHeaders([
                'API-Key' => config('taptalk.api_key'),
                'Content-Type' => 'application/json'
            ])->post(config('taptalk.send_message_api'), [
                'phone' => $user->phone_number,
                'messageType' => config('taptalk.message_type'),
                'body' => $code
            ]);

            if ($response->ok()) {
                $status = $response->collect('status')->first();
                if ($status == 200) {
                    // We are good
                    $messageId = $response->collect('data')['id'];
                    if ($messageId) {
                        $otpCode = OtpCode::whereCode($code)
                            ->whereStatus('unverified')
                            ->first();
                        if ($otpCode) {
                            $otpCode->update([
                                'message_id' => $messageId
                            ]);
                        } else {
                            // Something wrong, why thereis no data (?)
                            throw new \Exception("Internal server error", 500);
                        }
                    }
                } else if ($status >= 400 && $status <= 499) {
                    // Something wrong with payloads or authentication
                    throw new \Exception("There is an error while communicating with taptalk service", 400);
                } else if ($status >= 500 && $status <= 599) {
                    // TapTaplk server error
                    throw new \Exception("There is an error while communicating with taptalk service", 500);
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
        throw new Exception("Unimplemented");
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

        if ($otp == 0)
            $this->generateOTP($length);

        return $otp;
    }
}
