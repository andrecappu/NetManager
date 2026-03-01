<?php

namespace App\Modules\Notifications\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Notifications\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->unreadNotifications()->paginate(15);
        return NotificationResource::collection($notifications);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return new NotificationResource($notification);
    }
}
