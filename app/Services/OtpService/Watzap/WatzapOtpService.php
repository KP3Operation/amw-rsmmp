<?php

namespace App\Services\OtpService\Watzap;

use App\Dto\WatzapDto\CheckNumberDto;
use App\Dto\WatzapDto\SendMessageDto;
use App\Models\User;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WatzapOtpService implements IWatzapOtpService
{
    public function sendOtp(User $user, string $code): SendMessageDto
    {
        Log::info("Sending OTP", [$user->phone_number]);

        $response = Http::withHeaders([
            'Content-Type' => "application/json"
        ])->withOptions([
            "verify" => false
        ])->post(config("watzap.send_message_url"), [
            "api_key" => config("watzap.api_key"),
            "number_key" => config("watzap.number_key"),
            "phone_no" => str_replace("+", "", $user->phone_number),
            "message" => __("login.otp_message", ["otpcode" => $code])
        ]);

        if (!$response->successful()) {
            Log::error("Error while communicating with watzap service", [$response->status(), $response->body()]);
            throw new HttpClientException("Unexpected error while communicating with watzap service", 500);
        }

        $data = $response->json();
        return SendMessageDto::from($data);
    }

    public function isPhoneNumberValid(User $user): bool
    {
        Log::info("Checking phone number is valid whatsApp phone number", [$user->phone_number]);

        $response = Http::withHeaders([
            'Content-Type' => "application/json"
        ])->withOptions([
            "verify" => false
        ])->post(config("watzap.validate_whatsapp_number_url"), [
            "api_key" => config("watzap.api_key"),
            "number_key" => config("watzap.number_key"),
            "phone_no" => str_replace("+", "", $user->phone_number)
        ]);

        if (!$response->successful()) {
            Log::error("Error while communicating with watzap service", [$response->status(), $response->body()]);
            throw new HttpClientException("Unexpected error while communicating with watzap service", 500);
        }

        $data = $response->json();
        $result = CheckNumberDto::from($data);

        if ($result->message != "Valid WhatsApp Number" || $result->status != "200") {
            return false;
        }

        return true;
    }
}
