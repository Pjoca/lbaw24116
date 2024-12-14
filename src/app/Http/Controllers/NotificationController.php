<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class NotificationController extends Controller
{
    /**
     * Add a new notification for a user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addNotification(Request $request)
    {

        $this->authorize('addAndGet', Notification::class);

        $request->validate([
            'id_user' => 'required|integer|exists:user,id',
            'message_content' => 'required|string',
            'onclick_url' => 'nullable|string'
        ]);

        $notification = Notification::create([
            'id_user' => $request->id_user,
            'message_content' => $request->message_content,
            'onclick_url' => $request->onclick_url,
            'created_at' => now(),
        ]);

        $pusher = new Pusher(
          env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true
            ]
        );

        $pusher->trigger('private-notification.' . $request->id_user, 'new-notification', [
            'notification' => $notification
        ]);

        return response()->json(['success' => true, 'notification' => $notification], 201);
    }

    /**
     * Delete a notification by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNotification($id)
    {
        $notification = Notification::findOrFail($id);

        $this->authorize('delete', $notification);

        $notification->delete();

        return response()->json(['success' => true, 'message' => 'Notification deleted successfully.']);
    }

    /**
     * Get all notifications for a specific user.
     *
     * @param int $id_user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications($id_user)
    {
        $this->authorize('addAndGet', Notification::class);

        $notifications = Notification::where('id_user', $id_user)->orderBy('created_at', 'desc')->get();

        return response()->json(['success' => true, 'notifications' => $notifications]);
    }
}