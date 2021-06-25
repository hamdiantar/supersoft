<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\CustomerReservation;
use Exception;

class Reservations extends Controller
{
    const view_path = 'admin.customer-reservations.';

    function index(Request $req) {
        if (!auth()->user()->can('view_reservations')) {
            return redirect('/admin')->with(['authorization' => 'error']);
        }
        if (authIsSuperAdmin()) return redirect('/admin');
        $lang = app()->getLocale() == 'ar' ? 'ar' : 'en';
        $date_from = $req->has('date_from') ? $req->date_from : NULL;
        $date_to = $req->has('date_to') ? $req->date_to : date('Y-m-d');
        $collection = CustomerReservation::join('customers' ,'customers.id' ,'=' ,'customer_reservations.customer_id')
        ->where('customers.branch_id' ,auth()->user()->branch_id)
        ->when($date_from ,function ($q) use ($date_from ,$date_to) {
            $q->whereDate('customer_reservations.event_date' ,'>=' ,$date_from)
            ->whereDate('customer_reservations.event_date' ,'<=' ,$date_to);
        })
        ->select(
            'customers.name_'.$lang.' as cust_name',
            'customers.id as cust_id',
            'customers.phone1 as cust_phone',
            'customer_reservations.id as reservation_id',
            'customer_reservations.event_date as reservation_date',
            'customer_reservations.event_time as reservation_time',
            'customer_reservations.event_title as reservation_title',
            'customer_reservations.status as reservation_status',
        )
        ->orderBy('customer_reservations.event_date' ,'desc');
        return view(self::view_path . 'index' ,['collection' => $collection ,'row_view_path' => self::view_path . 'row']);
    }

    function getReservation($id) {
        if (!auth()->user()->can('take_action_reservations')) {
            return response(['message' => __('reservations.not-allowed')] ,400);
        }
        try {
            $reservation = CustomerReservation::findOrFail($id);
        } catch (Exception $e) {
            return response(['message' => __('reservations.not-found')] ,400);
        }
        return response(['code' => view(self::view_path . 'form' ,compact('reservation'))->render()]);
    }

    function update($id) {
        if (!auth()->user()->can('take_action_reservations')) {
            return response(['message' => __('reservations.not-allowed')] ,400);
        }
        if (!request()->has('status')) return response(['status' => 400 ,'message' => __('reservations.status-required')]);
        if (!in_array(request('status') ,['approved' ,'rejected']))
            return response(['status' => 400 ,'message' => __('reservations.status-available')]);
        if (request('status') == 'rejected' && request('admin_comment') == '')
            return response(['status' => 400 ,'message' => __('reservations.admin-comment-required')]);
        try {
            $reservation = CustomerReservation::findOrFail($id);
        } catch (Exception $e) {
            return response(['status' => 400 ,'message' => __('reservations.not-found')]);
        }
        $reservation->update([
            'admin_comment' => request('admin_comment'),
            'status' => request('status')
        ]);
        return response(['status' => 200 ,'message' => __('reservations.admin-' . request('status'))]);
    }
}
