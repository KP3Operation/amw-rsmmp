<?php

namespace App\Services\OtpService\Watzap;

use App\Dto\WatzapDto\CheckApiKeyDto;
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
        $result =  SendMessageDto::from($data);

        // Response status
        // 200 Success
        // 1002 Invalid API Key
        // 1003 Invalid Number Key
        // 1004 Pairing Failed, Access Denied
        // 1005 Fatal Error with Dynamic Message
        // 1006 Other Error

        if ($result->status == 200) {
            Log::info("OTP sent successfully", [$user->phone_number]);
        }

        if ($result->status == 1002) {
            Log::error("Invalid API Key", [$user->phone_number]);
            throw new HttpClientException("WatZap API key tidak valid", 500);
        }

        if ($result->status == 1003) {
            Log::error("Invalid Number Key", [$user->phone_number]);
            throw new HttpClientException("WatZap number key tidak valid", 500);
        }

        if ($result->status == 1004) {
            Log::error("WatZap Pairing Failed, Access Denied", [$user->phone_number]);
            throw new HttpClientException("Gagal menghubungkan dengan whatsapp", 500);
        }

        if ($result->status == 1005) {
            Log::error("WatZap Fatal Error with Dynamic Message", [$user->phone_number, $result->message]);
            throw new HttpClientException("{$result->message}", 500);
        }

        if ($result->status == 1006) {
            Log::error("WatZap unknown error", [$user->phone_number]);
            throw new HttpClientException("Gagal mengirimkan OTP", 500);
        }

        return $result;
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

        if ($result->status == 200) {
            Log::info("OTP sent successfully", [$user->phone_number]);
        }

        if ($result->status == 1002) {
            Log::error("Invalid API Key", [$user->phone_number]);
            throw new HttpClientException("WatZap API key tidak valid", 500);
        }

        if ($result->status == 1003) {
            Log::error("Invalid Number Key", [$user->phone_number]);
            throw new HttpClientException("WatZap number key tidak valid", 500);
        }

        if ($result->status == 1004) {
            Log::error("WatZap Pairing Failed, Access Denied", [$user->phone_number]);
            throw new HttpClientException("Gagal menghubungkan dengan whatsapp", 500);
        }

        if ($result->status == 1005) {
            Log::error("WatZap Fatal Error with Dynamic Message", [$user->phone_number]);
            throw new HttpClientException("Gagal mengirimkan OTP", 500);
        }

        if ($result->status == 1006) {
            Log::error("WatZap unknown error", [$user->phone_number]);
            throw new HttpClientException("Gagal mengirimkan OTP", 500);
        }

        if ($result->message != "Valid WhatsApp Number" || $result->status != "200") {
            return false;
        }

        return true;
    }

    /**
     * @throws HttpClientException
     * @throws \Exception
     */
    public function isApiKeyValid(): CheckApiKeyDto
    {
        Log::info("Checking is api key valid", [config("watzap.api_key")]);

        $response = Http::withHeaders([
            'Content-Type' => "application/json"
        ])->withOptions([
            "verify" => false
        ])->post(config("watzap.validate_api_key_url"), [
            "api_key" => config("watzap.api_key")
        ]);

        if (!$response->successful()) {
            Log::error("Error while communicating with watzap service", [$response->status(), $response->body()]);
            throw new HttpClientException("Unexpected error while communicating with watzap service", 500);
        }

        $data = $response->json();
        $result = CheckApiKeyDto::from($data);

        if (!$result->status) {
            // TODO: trigger slack notification, email, etc...
            throw new \Exception("API KEY WatZap tidak valid", 500);
        }

        return $result;
    }
}
