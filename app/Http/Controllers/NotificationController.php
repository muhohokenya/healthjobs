<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index(){
        // Get all unread notifications for the authenticated user
        $unreadNotifications = auth()->user()
            ->unreadNotifications()
            ->orderBy('created_at', 'desc')
            ->get();

        // Optional: Get all notifications (read and unread) with pagination
        $allNotifications = auth()->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('Notification/Index', [
            'unreadNotifications' => $unreadNotifications,
            'allNotifications' => $allNotifications,
            'unreadCount' => $unreadNotifications->count(),
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Request $request, $notificationId)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return back();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back();
    }

    /**
     * Get only unread notifications (API endpoint)
     */
    public function unread()
    {
        $unreadNotifications = auth()->user()
            ->unreadNotifications()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'notifications' => $unreadNotifications,
            'count' => $unreadNotifications->count(),
        ]);
    }
}
