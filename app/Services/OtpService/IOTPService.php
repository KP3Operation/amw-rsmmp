<?php

namespace App\Services\OtpService;

use App\Models\OtpCode;
use App\Models\User;

interface IOTPService
{
    public function sendOtp(User $user): OtpCode;

    public function getStatus(): bool;
}
