<?php

namespace App\Http\Controllers\Web;

use App\Models\Car;
//<<<<<<< HEAD
use App\Models\CardInvoice;
use App\Models\CardInvoiceType;
use App\Models\Customer;
use App\Models\Setting;
use App\Models\Shift;
//=======
//>>>>>>> 3429317d556a7e2f9467308a57cd957df68bc79a
use App\Models\User;
use App\Models\Branch;
use App\Models\WorkCard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\CustomerPanel\WorkCards;

class WorkCardsController extends Controller
{
    public function index(Request $request) {

        $auth = auth()->guard('customer')->user();

        $cards  = WorkCard::query()->where('customer_id',$auth->id);

        if($request->has('car_id') && $request['car_id'] != ''){
            $cards->where('car_id', $request['car_id']);
        }

        if($request->has('card_number') && $request['card_number'] != ''){
            $cards->where('card_number', $request['card_number']);
        }

        if($request->has('card_status') && $request['card_status'] != ''){
            $cards->where('status', $request['card_status']);
        }

        if($request->has('date_from') && $request['date_from'] != ''){
            $cards->whereDate('created_at','>=',$request['date_from']);
        }

        if($request->has('date_to') && $request['date_to'] != ''){
            $cards->whereDate('created_at','<=',$request['date_to']);
        }

        if($request->has('invoice_number') && $request['invoice_number'] != ''){
            $cards->whereHas('cardInvoice', function ($q) use($request){
                $q->where('id', $request['invoice_number']);
            });
        }

        if($request->has('receive_car_status') && $request['receive_car_status'] != ''){
            $cards->where('receive_car_status',1);
        }

        if($request->has('not_receive_car_status') && $request['not_receive_car_status'] != ''){
            $cards->where('receive_car_status',0);
        }

        if($request->has('delivery_car_status') && $request['delivery_car_status'] != ''){
            $cards->where('delivery_car_status',1);
        }

        if($request->has('not_delivery_car_status') && $request['not_delivery_car_status'] != ''){
            $cards->where('delivery_car_status',0);
        }

        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'card-number' => 'card_number',
                'name' => 'customer_id',
                'phone' => 'customer_id',
                'status' => 'status',
                'receive-status' => 'receive_car_status',
                'delivery-status' => 'delivery_car_status',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at'
            ];
            $cards = $cards->orderBy($sort_fields[$sort_by] ,$sort_method);
        } else {
            $cards = $cards->orderBy('id', 'DESC');
        }if ($request->has('key')) {
            $key = $request->key;
            $cards->where(function ($q) use ($key) {
                $q->where('card_number' ,'like' ,"%$key%")
                ->orWhere('created_at' ,'like' ,"%$key%")
                ->orWhere('updated_at' ,'like' ,"%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker ,['print' ,'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new WorkCards($cards->with('customer') ,$visible_columns), $request->invoker))();
        }
        $rows = $request->has('rows') ? $request->rows : 10;
        $cards = $cards->paginate($rows)->appends(request()->query());

        $data['cars'] = Car::where('customer_id',$auth->id)->get()->pluck('plate_number','id');
        $data['cards'] = WorkCard::where('customer_id',$auth->id)->get();
        $data['card_invoices'] = CardInvoice::whereHas('workCard', function ($q) use($auth) {

            $q->where('customer_id', $auth->id);

        })->get()->pluck('inv_number','id');

        return view('web.work_cards.index', compact('cards','data'));
    }

    public function show(Request $request) {

        $type = $request['type'];

        $work_card = WorkCard::findOrFail($request['invoiceID']);

        $setting = Setting::where('branch_id', $work_card->branch_id)->where('maintenance_status', 1)->first();

        $winchType = null;

        if ($work_card->cardInvoice) {

            $winchType = CardInvoiceType::where('card_invoice_id', $work_card->cardInvoice->id)->where('type','Winch')->first();
        }

        $invoice = view('web.work_cards.show',compact('work_card','type','setting', 'winchType'))->render();

        return response()->json(['invoice' => $invoice]);
    }
}
