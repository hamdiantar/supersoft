<?php

namespace App\Http\Controllers\Web;

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

        $auth = Auth::guard('customer')->user();

        $notification = $auth->notifications()->where('id', $notification)->first();

        $notification->markAsRead();

        if ($notification->type == 'App\Notifications\CustomerWorkCardStatusNotification'){

            return redirect(route('web:work.cards.index'));
        }

        if ($notification->type == 'App\Notifications\QuotationStatusNotification'){

            return redirect(route('web:quotations.index'));
        }

        if ($notification->type == 'App\Notifications\SalesInvoiceNotification'){

            return redirect(route('web:sales.invoices.index'));
        }

        if ($notification->type == 'App\Notifications\ReturnSalesInvoiceNotification'){

            return redirect(route('web:sales.invoices.return.index'));
        }

        if ($notification->type == 'App\Notifications\SalesInvoicePaymentsNotification'){

            return redirect(route('web:sales.invoices.index'));
        }

        if ($notification->type == 'App\Notifications\SalesInvoiceReturnPaymentNotification'){

            return redirect(route('web:sales.invoices.return.index'));
        }

        if ($notification->type == 'App\Notifications\WorkCardPaymentsNotification'){

            return redirect(route('web:work.cards.index'));
        }

//        if ($notification->type == 'App\Notifications\ReservationNotification'){
//
//            return redirect(route('web:reservations.index'));
//        }
    }

    public function getRealTimeNotification (Request  $request) {

        $auth = Auth::guard('customer')->user();

        $notification = $auth->notifications()->where('id', $request['notification']['id'])->first();

        $count = $auth->notifications()->where('read_at', null)->count();

        $view = view('web.notifications.ajax_notification', compact('notification'))->render();

        return response()->json(['view'=> $view, 'count'=> $count], 200);
    }
}
