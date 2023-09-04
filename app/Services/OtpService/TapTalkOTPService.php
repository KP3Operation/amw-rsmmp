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

        SendTapTalkOtp::dispatch($code, $user->phone_number);

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
