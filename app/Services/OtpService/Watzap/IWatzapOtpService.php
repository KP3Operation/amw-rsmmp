<?php

namespace App\Services\OtpService\Watzap;

use App\Dto\WatzapDto\SendMessageDto;

interface IWatzapOtpService
{
    public function sendOtp(string $phoneNumber, string $code): SendMessageDto;
}
