<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    //
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
        ->whereNull('read_at') // solo no leídas
        ->orderBy('created_at', 'desc')
        ->get();

        return view('driver.notifications.index_notifications', compact('notifications'));
    }

    public function markRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();
        $notification->update(['read_at' => now()]);
        return redirect()->back();
    }

    public function json()
    {
        $notifications = Notification::where('user_id', Auth::id())
        ->whereNull('read_at') // solo no leídas
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json($notifications);
    }
}
