<?php

namespace App\Services\OtpService\Watzap;

use App\Dto\WatzapDto\CheckNumberDto;
use App\Dto\WatzapDto\SendMessageDto;
use App\Models\User;

interface IWatzapOtpService
{
    public function sendOtp(User $user, string $code): SendMessageDto;
    public function isPhoneNumberValid(User $user): bool;
}
