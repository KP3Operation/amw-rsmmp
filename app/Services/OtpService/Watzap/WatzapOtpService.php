<?php

namespace App\Services\OtpService\Watzap;

use App\Dto\WatzapDto\SendMessageDto;
use App\Exceptions\WatzapException;
use App\Services\OtpService\IOtpBaseApi;
use Illuminate\Support\Facades\Log;

class WatzapOtpService implements IWatzapOtpService
{
    private IOtpBaseApi $otpBaseApi;

    public function __construct(IOtpBaseApi $otpBaseApi)
    {
        $this->otpBaseApi = $otpBaseApi;
    }

    /**
     * @throws WatzapException
     */
    public function sendOtp(string $phoneNumber, string $code): SendMessageDto
    {
        Log::info("Mengirimkan OTP ke {$phoneNumber}", [$phoneNumber]);

        $response = $this->otpBaseApi->post('', [], [
            'phone_no' => str_replace('+', '', $phoneNumber),
            'message' => __('login.otp_message', ['otpcode' => $code]),
        ]);

        if (! $response->successful()) {
            Log::error('Gagal menghubungi WatZap OTP', [$response->status(), $response->body()]);
            throw new WatzapException('Gagal menghubungi WatZap OTP, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();
        $result = SendMessageDto::from($data);

        // Response status
        // 200 Success
        // 1002 Invalid API Key
        // 1003 Invalid Number Key
        // 1004 Pairing Failed, Access Denied
        // 1005 Fatal Error with Dynamic Message
        // 1006 Other Error

        if ($result->status == 200) {
            Log::info('OTP sent successfully', [$phoneNumber]);
        }

        if ($result->status == 1002) {
            Log::error('Invalid API Key', [$phoneNumber]);
            throw new WatzapException('WatZap API key tidak valid', 500);
        }

        if ($result->status == 1003) {
            Log::error('Invalid Number Key', [$phoneNumber]);
            throw new WatzapException('WatZap number key tidak valid', 500);
        }

        if ($result->status == 1004) {
            Log::error('WatZap Pairing Failed, Access Denied', [$phoneNumber]);
            throw new WatzapException('Gagal menghubungkan dengan WhatsApp', 500);
        }

        if ($result->status == 1005) {
            Log::error('WatZap Fatal Error with Dynamic Message', [$phoneNumber, $result->message]);
            // If it is local env just ignore all dynamic error message; for dev only
            if (config('app.env') != 'local') {
                throw new WatzapException("{$result->message}", 500);
            }
        }

        if ($result->status == 1006) {
            Log::error('WatZap unknown error', [$phoneNumber]);
            throw new WatzapException('Gagal mengirimkan OTP', 500);
        }

        return $result;
    }
}
