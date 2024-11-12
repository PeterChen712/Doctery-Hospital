<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function notifications()
    {
        $notifications = UserNotification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function markNotificationAsRead(UserNotification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function destroy(UserNotification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json(['message' => 'Notification deleted']);
    }
}