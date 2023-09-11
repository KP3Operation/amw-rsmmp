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
