<?php

use App\Models\OtpCode;
use App\Models\User;
use Carbon\Carbon;

if (!function_exists('user_role')) {
    function user_role(int $userid)
    {
        $user = User::with('roles')->where('id', '=', $userid)->first();
        $roleName = "patient";
        foreach ($user->roles as $role) {
            $roleName = $role->name;
            break;
        }

        return $roleName;
    }
}

if (!function_exists('parse_microsoft_date')) {
    function parse_microsoft_date(string $date): DateTime|bool
    {
        $timestamp = (int) substr($date, 6, -2) / 1000; // Extract the timestamp value
        return DateTime::createFromFormat('U', $timestamp);
    }
}

if (!function_exists('generate_otp')) {
    function generate_otp(int $length): int
    {
        $otp = '0';
        $characters = '0123456789'; // possible characters for the otp

        $charcount = strlen($characters);
        while (strlen($otp) <= $length) {
            $otp .= $characters[rand(1, $charcount - 1)];
        }

        $otp = (int) $otp;

        $existingcode = otpcode::where('code', '=', $otp)->first();

        if ($otp == 0 || $existingcode != null)
            generate_otp($length);

        return $otp;
    }
}

if (!function_exists('date_diff_in_second')) {
    function date_diff_in_second($pastDate): int
    {
        $currentDate = Carbon::parse(date('Y-m-d H:i:s'));
        $pastDate = Carbon::parse($pastDate);

        return $currentDate->diffInSeconds($pastDate);
    }
}

if (!function_exists('convert_date_to_req_param')) {
    function convert_date_to_req_param(string $date): string
    {
        $parsedDate = Carbon::parse($date);
        return $parsedDate->format('Y-m-d');
    }
}

if (!function_exists('get_current_month_start_date')) {
    function get_current_month_start_date(): string
    {
        return date('Y-m-01');
    }
}

if (!function_exists('get_current_month_date')) {
    function get_current_month_date(): string
    {
        return date('Y-m-d');
    }
}

if (!function_exists('get_current_year_start_date')) {
    function get_current_year_start_date(): string
    {
        return date('Y-01-01');
    }
}
