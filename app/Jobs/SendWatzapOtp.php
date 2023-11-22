<?php

namespace App\Jobs;

use App\Services\OtpService\Watzap\IWatzapOtpService;
use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class SendWatzapOtp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private IWatzapOtpService $otpService;
    private string $phoneNumber;
    private int $code;

    public function __construct(IWatzapOtpService $otpService, string $phoneNumber, int $code)
    {
        $this->otpService = $otpService;
        $this->phoneNumber = $phoneNumber;
        $this->code = $code;
    }

    public function handle(): void
    {
        Log::info("Send OTP for {phoneNumber} with code {code}", ['phoneNumber' => $this->phoneNumber, 'code' => $this->code]);

        $this->otpService->sendOtp($this->phoneNumber, $this->code);
    }
}
