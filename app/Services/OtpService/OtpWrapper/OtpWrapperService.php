<?php

namespace App\Services\OtpService\OtpWrapper;

use App\Exceptions\InvalidWhatsAppPhoneNumber;
use App\Jobs\SendWatzapOtp;
use App\Models\OtpCode;
use App\Models\User;
use App\Services\OtpService\Watzap\IWatzapOtpService;
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

        if (!$this->otpService->isPhoneNumberValid($user)) {
            Log::error("Phone number {phoneNumber} is not valid", ["phoneNumber" => $user->phone_number]);
            $user->delete();
            throw new InvalidWhatsAppPhoneNumber(__("register.errros.invalid_whatsapp_phone_number"), 500);
        }

        $code = generate_otp(6);

        if (config("app.otp_with_queue")) {
            SendWatzapOtp::dispatch($user->phone_number, $code);
        } else {
            $this->otpService->sendOtp($user, $code);
        }

        $otpCode = OtpCode::updateOrcreate([
            'user_id' => $user->id
        ], [
            'code' => $code,
            'status' => 'unverified',
            'message_id' => null
        ]);

        return $otpCode;
    }
}
