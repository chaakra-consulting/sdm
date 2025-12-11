<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class NotificationController extends Controller
{
    private function fixActionUrl($actionUrl)
    {
        
    }   

    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->ajax() || $request->wantsJson()) {
            $limit = $request->input('limit', 5);
            $notifications = $user->notifications()->latest()->limit($limit)->get();

            $formattedNotifications = $notifications->map(function ($notif){
                return [
                    'id' => $notif->id,
                    'title' => 'Notifikasi',
                    'message' => $notif->data['message'] ?? 'Notifikasi baru',
                    'action_url' => $notif->data['action_url'] ?? '#',
                    'created_at_human' => $notif->created_at->diffForHumans(),
                    'type' => $notif->type === 'laporan_kinerja_rejected' ? 'alert' : 'success',
                    'read_at' => $notif->read_at
                ];
            });

            return response()->json(['data' => $formattedNotifications]);
        }

        $title = 'Notifikasi';
        $notifications = $user->notifications()
                            ->orderBy('created_at','desc')
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