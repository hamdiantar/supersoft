<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class NotificationsController extends Controller
{
    public function index () {

        return view('admin.notifications.index');
    }

    public function goToLink ($notification) {

        $auth = Auth::user();

        $notification = $auth->notifications()->where('id', $notification)->first();

        $notification->markAsRead();

        if ($notification->type == 'App\Notifications\RegisterCustomersNotifications'){

            return redirect(route('admin:customers.requests.index'));
        }

        if ($notification->type == 'App\Notifications\QuotationRequestNotification'){

            return redirect(route('admin:quotations.requests.index'));
        }

        if ($notification->type == 'App\Notifications\WorkCardStatusNotification'){

            return redirect(route('admin:work-cards.index'));
        }

        if ($notification->type == 'App\Notifications\ReservationNotification'){

            return redirect(route('admin:reservations.index'));
        }
    }

    public function getRealTimeNotification (Request  $request) {

        $auth = Auth::user();

        $notification = $auth->notifications()->where('id', $request['notification']['id'])->first();

        $count = $auth->notifications()->where('read_at', null)->count();

        $view = view('admin.notifications.ajax_notification', compact('notification'))->render();

        return response()->json(['view'=> $view, 'count'=> $count], 200);
    }
}
