<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Session;

class LogoutController extends Controller
{
    public function invalidateSession(Request $request)
    {
        $user = $request->user();

        Session::flush();

        // Auth::logout($user->password);

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $user->tokens()->delete();

        return response()->json([], 204);
    }
}
