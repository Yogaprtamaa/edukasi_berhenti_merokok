<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest('sent_at')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }
}
