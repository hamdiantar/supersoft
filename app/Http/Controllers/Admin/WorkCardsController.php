<?php

namespace App\Http\Controllers\Admin;

use App\Models\Car;
use App\Models\CardInvoiceType;
use App\Models\User;
use App\Models\Shift;
use App\Models\Branch;
use App\Models\Setting;
use App\Models\Customer;
use App\Models\WorkCard;
use App\Models\CardInvoice;
use App\Notifications\CustomerWorkCardStatusNotification;
use App\Notifications\WorkCardStatusNotification;
use App\Services\MailServices;
use App\Services\NotificationServices;
use Illuminate\Http\Request;
use App\Models\CustomerCategory;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DataExportCore\WorkCards;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Requests\Admin\WorkCards\CreateWorkCardRequest;
use App\Http\Requests\Admin\WorkCards\UpdateWorkCardRequest;
use Illuminate\Support\Facades\Notification;

class WorkCardsController extends Controller
{

    use NotificationServices, MailServices;

    public function index(Request $request){

        if (!auth()->user()->can('view_work_card')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $cards  = WorkCard::query();
        $cards->globalCheck($request);

        if($request->has('branch_id') && $request['branch_id'] != ''){
            $cards->where('branch_id', $request['branch_id']);
        }

        if($request->has('user_id') && $request['user_id'] != ''){
            $cards->where('created_by', $request['user_id']);
        }

        if($request->has('shift_id') && $request['shift_id'] != ''){
            $cards->whereHas('user', function ($q) use($request){
                $q->whereHas('shifts',function ($shift) use($request){
                    $shift->where('shift_id', $request['shift_id']);
                });
            });
        }

        if($request->has('customer_id') && $request['customer_id'] != ''){
            $cards->where('customer_id', $request['customer_id']);
        }

        if($request->has('phone') && $request['phone'] != ''){
            $cards->whereHas('customer', function ($q) use($request){
                $q->where('id', $request['phone']);
            });
        }

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

        $data['shifts'] = filterSetting() ? Shift::all()->pluck('name','id') : null;
        $data['branches'] = filterSetting() ? Branch::all()->pluck('name','id') : null;
        $data['cars'] = filterSetting() ? Car::all()->pluck('plate_number','id') : null;
        $data['users'] = filterSetting() ? User::orderBy('id','ASC')->branch()->get()->pluck('name','id') : null;
        $data['customers'] = filterSetting() ? Customer::orderBy('id','ASC')->get() : null;
        $data['cards'] = filterSetting() ? WorkCard::get() : null;
        $data['card_invoices'] = filterSetting() ? CardInvoice::all()->pluck('inv_number','id') : null;

        return view('admin.work_cards.index', compact('cards','data'));
    }

    public function create(){

        if (!auth()->user()->can('create_work_card')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::where('status',1)->get()->pluck('name','id');
        $customers = Customer::where('status',1)->get();
        $customerGroups = CustomerCategory::where('status', 1);

        if(!authIsSuperAdmin()){
            $customerGroups->where('branch_id', auth()->user()->branch_id);
        }

        $customerGroups = $customerGroups->get();

        return view('admin.work_cards.create', compact('customers','branches','customerGroups'));
    }

    public function getCustomersByBranch(Request $request){
        $data['customers'] = [];

        Customer::where('branch_id', $request['branch_id'])->orderBy('id' ,'desc')->chunk(100 ,function ($customers) use (&$data) {
            foreach($customers as $customer) {
                $d = $customer->toArray();
                $d['balance_details'] = $customer->get_my_balance();
                array_push($data['customers'] ,$d);
            }
        });

        $data['customerGroups'] = CustomerCategory::where('status', 1)->where('branch_id', $request['branch_id'])->get();

        return $data;
    }

    public function store(CreateWorkCardRequest $request){

        if (!auth()->user()->can('create_work_card')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{

            $data = $request->validated();

            $data['created_by'] = auth()->id();

            if(!authIsSuperAdmin()){
                $data['branch_id'] = auth()->user()->branch_id;
            }

            $data['card_number'] = generateNumber($data['branch_id'],'App\Models\WorkCard','card_number');

            if($request->has('receive_car_status')){
                $data['receive_car_status'] = 1;
            }

            if($request->has('delivery_car_status')) {
                $data['delivery_car_status'] = 1;
            }

            $work_card =  WorkCard::create($data);

            $this->sendNotification('work_card_status_to_user','admins',
                [
                    'work_card' => $work_card,
                    'message'=> 'New Work Card Created With Status Pending'
                ]);

            $this->sendNotification('work_card_status_to_customer','customer',
                [
                    'work_card' => $work_card,
                    'message'=> 'New Work Card Created With Status Pending'
                ]);

            if ($work_card->customer && $work_card->customer->email) {

                $this->sendMail($work_card->customer->email,'work_card_send_status','work_card_create',
                    'App\Mail\WorkCard');
            }

        }catch (\Exception $e){
//            dd($e->getMessage());
            return redirect()->back()->with(['message'=> __('words.try-again'),'alert-type'=>'error']);
        }

        if($request->has('save_type') && $request['save_type'] == 'save_with_invoice' ){

            return redirect(route('admin:work.cards.invoice.create',$work_card))
                ->with(['message'=> __('words.card-created'),'alert-type'=>'success']);
        }

        return redirect(route('admin:work-cards.index'))
            ->with(['message'=> __('words.card-created'),'alert-type'=>'success']);
    }

    public function show(Request $request){

        if (!auth()->user()->can('view_work_card')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $type = $request['type'];

        $work_card = WorkCard::findOrFail($request['invoiceID']);

        $setting = Setting::where('branch_id', auth()->user()->branch_id)->where('maintenance_status', 1)->first();

        $winchType = null;

        if ($work_card->cardInvoice) {

            $winchType = CardInvoiceType::where('card_invoice_id', $work_card->cardInvoice->id)->where('type','Winch')->first();
        }

        $invoice =  view('admin.work_cards.show',compact('work_card','type','setting', 'winchType'))->render();

        return response()->json(['invoice' => $invoice]);
    }

    public function getCustomersCars(Request $request){

        $customer = Customer::findOrFail($request['customer_id']);
        $cars = $customer->cars;
        return view('admin.work_cards.ajax_customer_cars',compact('cars'));
    }

    public function selectCustomersCar(Request $request){

        $customer = Customer::findOrFail($request['customer_id']);

        if($customer->cars->count() == 1){
            $car = $customer->cars->first();
            return $data['car'] = $car;
        }

        if($request['type'] == 'select_customer'){
            return false;
        }

        $customer_cars_ids = $customer->cars->pluck('id')->toArray();

        if(!in_array($request['id'], $customer_cars_ids)){
            return response()->json(__('words.car-not-belong-to-customer'),400);
        }

        $car = Car::findOrFail($request['id']);

        return $data['car'] = $car;
    }

    public function edit(WorkCard $workCard){

        if (!auth()->user()->can('update_work_card')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::where('status',1)->get()->pluck('name','id');
        $customers = Customer::where('status',1)->where('branch_id', $workCard->branch_id)->get();
       return view('admin.work_cards.edit',compact('workCard','branches','customers'));
    }

    public function update(UpdateWorkCardRequest $request, WorkCard $workCard){

        if (!auth()->user()->can('update_work_card')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{

            $data = $request->validated();

            if($workCard->cardInvoice){

                $data['customer_id'] = $workCard->customer_id;
                $data['branch_id']   = $workCard->branch_id;
                $data['car_id']      = $workCard->car_id;
            }

            if(!authIsSuperAdmin()){
                $data['branch_id'] = auth()->user()->branch_id;
            }

            $data['receive_car_status'] = 0;

            if($request->has('receive_car_status')){
                $data['receive_car_status'] = 1;
            }

            $data['delivery_car_status'] = 0;

            if($request->has('delivery_car_status')){
                $data['delivery_car_status'] = 1;
            }

            $workCard->update($data);

            $workCard->followUps()->delete();

            if ($workCard->status == 'processing') {

                foreach($request['followUpsItems'] as $item){

                    $workCard->followUps()->create(
                        [
                            'created_by'=> auth()->id(),
                            'name'=> $request['name_'.$item],
                            'kilo_number'=> $request['kilo_number_'.$item],
                            'date'=> $request['date_'.$item],
                            'notes'=> $request['notes_'.$item],
                            'status'=> $request['status_'.$item] ? 1:0,
                        ]
                    );
                }

                $workCard->status = 'scheduled';
                $workCard->save();
            }

        }catch (\Exception $e){
//            dd($e->getMessage());
            return redirect()->back()->with(['message'=> __('words.try-again'),'alert-type'=>'error']);
        }

        try {

            $this->sendNotification('work_card_status_to_customer','customer',
                [
                    'work_card' => $workCard,
                    'message'=> 'Your Work Card Updated, please check'
                ]);

            if ($workCard->customer && $workCard->customer->email) {

                $this->sendMail($workCard->customer->email,'work_card_send_status','work_card_edit', 'App\Mail\WorkCard');
            }

        }catch (\Exception $e) {

            if($request->has('save_type') && $request['save_type'] == 'save_with_invoice' ){

                $route = route('admin:work.cards.invoice.create', $workCard);

                if ($workCard->cardInvoice && !$workCard->cardInvoice->maintenanceDetectionTypes->count()) {

                    $route = route('admin:work.cards.invoice.sample.create', $workCard->id);
                }

                return redirect($route)->with(['message'=> __('words.card-updated'),'alert-type'=>'success']);
            }

            return redirect(route('admin:work-cards.index'))
                ->with(['message'=> __('words.card-updated'),'alert-type'=>'success']);

        }

        if($request->has('save_type') && $request['save_type'] == 'save_with_invoice' ){

            $route = route('admin:work.cards.invoice.create', $workCard);

            if ($workCard->cardInvoice && !$workCard->cardInvoice->maintenanceDetectionTypes->count()) {

                $route = route('admin:work.cards.invoice.sample.create', $workCard->id);
            }

            return redirect($route)->with(['message'=> __('words.card-updated'),'alert-type'=>'success']);
        }

        return redirect(route('admin:work-cards.index'))
            ->with(['message'=> __('words.card-updated'),'alert-type'=>'success']);

    }

    public function destroy(WorkCard $workCard){

        if (!auth()->user()->can('delete_work_card')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $workCard->delete();

        $this->sendNotification('work_card_status_to_customer','customer',
            [
                'work_card' => $workCard,
                'message'=> 'Your Work Card Deleted, please check'
            ]);

        if ($workCard->customer && $workCard->customer->email) {

            $this->sendMail($workCard->customer->email,'work_card_send_status','work_card_delete', 'App\Mail\WorkCard');
        }

        return redirect(route('admin:work-cards.index'))
            ->with(['message'=> __('words.card-deleted'),'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_work_card')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {
            if (isset($request->ids) && is_array($request->ids)) {
                WorkCard::whereIn('id', array_unique($request->ids))->delete();
                return redirect()->back()
                    ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => __('words.try-again'), 'alert-type' => 'error']);
        }
        return redirect()->back()
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function addFollowUp(Request $request){

        $index = $request['div_count'] + 1;
        $view = view('admin.work_cards.follow_up.ajax_add_new_row',compact('index'))->render();
        return response()->json(['view'=>$view],200);
    }
}
