<?php

namespace App\Http\Controllers\Api\V1\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = [];
        if ($request->has('doctor_id') && $request->doctor_id != '') {
            $notifications = Notification::where('doctor_id', '=', $request->doctor_id)
                ->where('is_read', '=', false)->get();
        }

        return response()->json([
            'notifications' => $notifications,
            'count' => count($notifications),
        ]);
    }

    public function update(Notification $notification)
    {
        $notification->update([
            'is_read' => true,
        ]);

        return response()->json([], 204);
    }
}
