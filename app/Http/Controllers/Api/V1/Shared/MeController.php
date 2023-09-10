<?php

namespace App\Http\Controllers\Api\V1\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            "user" => $request->user()
        ]);
    }
}
