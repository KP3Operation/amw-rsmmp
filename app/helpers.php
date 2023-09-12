<?php

use App\Models\User;

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
