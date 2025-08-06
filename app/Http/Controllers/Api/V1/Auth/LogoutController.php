<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    public function invalidateSession(Request $request)
    {
        Session::flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([], 204);
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->tokens()->delete(); // Revoke all tokens
            $user->delete(); // Delete the user
        }

        return response()->json(['message' => 'Account deleted successfully'], 200);
    }
}
