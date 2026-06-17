<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PlanNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanNotificationController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        $notifications = PlanNotification::where('recipient_username', $request->username)
            ->orderByRaw('read_at is null desc')
            ->orderByDesc('created_at')
            ->limit(40)
            ->get();

        return response()->json([
            'res' => true,
            'data' => $notifications,
            'unread' => $notifications->whereNull('read_at')->count(),
        ], 200);
    }

    public function markRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'],
            'ids' => ['sometimes', 'array'],
            'ids.*' => ['integer', 'exists:plan_notifications,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        $query = PlanNotification::where('recipient_username', $request->username)
            ->whereNull('read_at');

        if ($request->filled('ids')) {
            $query->whereIn('id', $request->input('ids', []));
        }

        $query->update(['read_at' => now()]);

        return response()->json([
            'res' => true,
            'message' => 'Notificaciones marcadas como leídas.',
        ], 200);
    }
}
