<?php

namespace App\Services\OtpService\OtpWrapper;

use App\Exceptions\InvalidWhatsAppPhoneNumber;
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

        // To check api key status
        $this->otpService->isApiKeyValid();

        $code = generate_otp(6);

        // if (config('app.env') == 'local') {
        //     $code = 12345;
        // }

        if (config("app.otp_with_queue")) {
            SendWatzapOtp::dispatch($user->phone_number, $code);
        } else {
            $this->otpService->sendOtp($user, $code);
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
