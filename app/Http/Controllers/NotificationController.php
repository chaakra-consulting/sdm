<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
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
                    'type' => $notif->type, 
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
        $notification = Auth::user()->notifications()->where('id', $id)->firstOrFail();
                            
        $notification->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->notifications()->whereNull('read_at')->count();
        return response()->json(['count' => $count]);
    }
} 