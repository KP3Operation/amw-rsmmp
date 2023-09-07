<?php

namespace App\Services\OtpService;

use App\Models\OtpCode;
use App\Jobs\SendWatzapOtp;
use App\Models\User;

class WatzapOTPService implements IOTPService
{

    public function sendOtp(User $user): OtpCode
    {
        $code = $this->generateOTP(6);

        SendWatzapOtp::dispatch($user->phone_number, $code);

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
