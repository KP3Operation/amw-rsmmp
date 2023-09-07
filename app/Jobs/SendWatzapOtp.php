<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;


class SendWatzapOtp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $phoneNumber;
    private int $code;

    public function __construct(string $phoneNumber, int $code)
    {
       $this->phoneNumber = $phoneNumber;
       $this->code = $code;
    }

    public function handle(): void
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post(config('watzap.send_message_url'), [
            'api_key' => config('watzap.api_key'),
            'number_key' => config('watzap.number_key'),
            'phone_no' => str_replace("+", "", $this->phoneNumber),
            'message' => __("login.otp_message", ["otpcode" => $this->code])
        ]);

        if ($response->ok()) {
            $status = $response->collect('status')->first();
            $message = $response->collect('message')->first();
            if ($status == "200" || $message == "Successfully") {
                // We are good
            } else if ($status >=400 && $status <= 499) {
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
}
