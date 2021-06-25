<?php
namespace App\Http\Controllers\Web;

use App\Model\CustomerReservation;
use App\Http\Controllers\Controller;
use App\Services\NotificationServices;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Web\ReservationReq;
use Exception;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;

class Reservations extends Controller {

    use NotificationServices;

    const view_path = 'web.reservations.';

    function index() {
        if (isset($_GET['refresh_message'])) {
            return redirect(route('web:reservations.index'))->with(['message' => $_GET['refresh_message'] ,'alert-type' => 'success']);
        }
        $reservations = Auth::guard('customer')->user()->reservations;
        if (isset($reservations[0])) {
            $reservation = $reservations[0];
            $single_event = Calendar::event(
                $reservation->event_title, //event title
                false,
                $reservation->event_date.' '.$reservation->event_time, //start time
                $reservation->event_date.' '.$reservation->event_time, //end time
                $reservation->id
            );
            $calendar = Calendar::addEvent($single_event ,[
                'color' => $reservation->get_color(),
                'url' => route('web:reservations.show' ,['id' => $reservation->id])
            ]);
        } else {
            $calendar = NULL;
        }

        foreach($reservations as $index => $reservation) {
            if ($index == 0) continue;
            $single_event = Calendar::event(
                $reservation->event_title, //event title
                false,
                $reservation->event_date.' '.$reservation->event_time, //start time
                $reservation->event_date.' '.$reservation->event_time, //end time
                $reservation->id
            );
            $calendar->addEvent($single_event ,[
                'color' => $reservation->get_color(),
                'url' => route('web:reservations.show' ,['id' => $reservation->id])
            ]);
        }

        return view(self::view_path .'index' ,compact('calendar'));
    }

    function store(ReservationReq $request) {
        $data = $request->all();
        if ($data['event_date'] == date('Y-m-d') && $data['event_time'] < date('H:i')) {
            return redirect()->back()->withInput()->with(['message' => __('reservations.time-in-future') ,'alert-type' => 'error']);
        }
        $data['customer_id'] = Auth::guard('customer')->id();

        $customerReservation = CustomerReservation::create($data);

        $this->sendNotification('customer_reservation','user', ['customer_reservation' => $customerReservation]);

        return redirect()->back()->with(['message' => __('reservations.created'), 'alert-type' => 'success']);
    }

    function fetchEvent($id) {
        try {
            $reservation = CustomerReservation::findOrFail($id);
        } catch (Exception $e) {
            return response(['message' => __('reservations.not-found')] ,400);
        }
        return response(['code' => view(self::view_path . 'reservation' ,compact('reservation'))->render()]);
    }

    function updateEvent(ReservationReq $request ,$id) {
        try {
            $reservation = CustomerReservation::findOrFail($id);
        } catch (Exception $e) {
            return response(['message' => __('reservations.not-found') ,'status' => 400] ,400);
        }
        $data = $request->all();
        if ($data['event_date'] == date('Y-m-d') && $data['event_time'] < date('H:i')) {
            return response(['message' => __('reservations.time-in-future') ,'status' => 400] ,400);
        }
        $reservation->update($data);
        // here need to notify admins
        return response(['message' => __('reservations.updated')]);
    }
}
