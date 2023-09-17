<?php

namespace App\Services\OtpService\OtpWrapper;

use App\Models\User;

interface IOtpWrapperService
{
    public function sendOtp(User $user);
}
