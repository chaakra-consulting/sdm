<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $title = 'Notifikasi';

        $notifications = Auth::user()
                            ->notifications()
                            ->orderBy('created_at', 'desc')
                            ->paginate(20);

        return view('notifications.index', compact('title', 'notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()
                            ->notifications()
                            ->findOrFail($id);
                            
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications->count();
        return response()->json(['count' => $count]);
    }
}