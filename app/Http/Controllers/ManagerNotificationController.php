<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManagerNotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(12);

        return view('manager.notifications.index', compact('notifications'));
    }

    public function read(Request $request, string $id)
    {
        $n = $request->user()->notifications()->findOrFail($id);
        $n->markAsRead();

        $url = $n->data['url'] ?? null;
        return $url ? redirect($url) : back();
    }
}
