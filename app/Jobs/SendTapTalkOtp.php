<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\OtpCode;
use Throwable;

class SendTapTalkOtp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3; // retry 3 times if failed to communicate with taptalk server
    public $backoff = 3; // wait 3 seconds before retry

    private int $code;
    private string $phoneNumber;

    public function __construct(
        int $code, string $phoneNumber
    )
    {
       $this->code = $code;
       $this->phoneNumber = $phoneNumber;
    }

    public function handle(): void
    {
        $response = Http::withHeaders([
            'API-Key' => config('taptalk.api_key'),
            'Content-Type' => 'application/json'
        ])->post(config('taptalk.send_message_api'), [
            'phone' => $this->phoneNumber,
            'messageType' => config('taptalk.message_type'),
            'body' => $this->code
        ]);

        if ($response->ok()) {
            $status = $response->collect('status')->first();
            if ($status == 200) {
                // We are good
                $messageId = $response->collect('data')['id'];
                if ($messageId) {
                    $otpCode = OtpCode::whereCode($this->code)
                                        ->whereStatus('unverified')
                                        ->first();
                    if ($otpCode) {
                        $otpCode->update([
                            'message_id' => $messageId
                        ]);
                    } else {
                        // Something wrong, why thereis no data (?)
                    }
                }

            } else if ($status >=400 && $status <= 499) {
                // Something wrong with payloads or authentication
            } else if ($status >= 500 && $status <= 599) {
                // TapTaplk server error
            } else {
                // Don't know why it is error :)
            }

        } else {
            // TODO: Something wrong
        }
    }
}
