<?php

namespace App\Http\Controllers;

use App\Models\BengkelNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BengkelNotificationController extends Controller
{
    /**
     * Fetch notifications for the current user (siswa or admin_bengkel).
     */
    public function fetch()
    {
        $user = Auth::user();
        $query = BengkelNotification::query()->latest();

        if ($user->role === 'siswa') {
            $query->where('user_id', $user->id)->where('target_role', 'siswa');
        } elseif ($user->role === 'admin_bengkel') {
            $query->whereNull('user_id')->where('target_role', 'admin_bengkel');
        } else {
            return response()->json(['notifications' => [], 'unread_count' => 0]);
        }

        $notifications = $query->take(15)->get();
        $unreadCount   = (clone $query)->whereNull('read_at')->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    /**
     * Mark all notifications as read for the current user.
     */
    public function markAllRead()
    {
        $user = Auth::user();

        if ($user->role === 'siswa') {
            BengkelNotification::where('user_id', $user->id)
                ->where('target_role', 'siswa')
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        } elseif ($user->role === 'admin_bengkel') {
            BengkelNotification::whereNull('user_id')
                ->where('target_role', 'admin_bengkel')
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get unread count only (lightweight for polling).
     */
    public function unreadCount()
    {
        $user = Auth::user();

        if ($user->role === 'siswa') {
            $count = BengkelNotification::where('user_id', $user->id)
                ->where('target_role', 'siswa')
                ->whereNull('read_at')
                ->count();
        } elseif ($user->role === 'admin_bengkel') {
            $count = BengkelNotification::whereNull('user_id')
                ->where('target_role', 'admin_bengkel')
                ->whereNull('read_at')
                ->count();
        } else {
            $count = 0;
        }

        return response()->json(['unread_count' => $count]);
    }
}
