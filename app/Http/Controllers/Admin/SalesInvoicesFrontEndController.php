<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Part;
use App\Models\PointRule;
use App\Models\PurchaseInvoice;
use App\Models\SparePart;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SalesInvoicesFrontEndController extends Controller
{
    public function addCustomer(Request $request){
        try{

            $rules = $this->validationRules();

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()){
                return Response::json($validator->errors()->first(), 400);
            }

            $data = $request->all();

            if(false == authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }

            $customerAdded = Customer::create($data);


            $customers = Customer::where('status',1)->get();

        }catch (\Exception $e){
            return Response::json(__('words.back-support'), 400);
        }
        $customer = Customer::find($customerAdded->id);

        $customerData = view('admin.sales-invoices.customers.ajax_customer',compact('customers','customer'))->render();
        $customerDataViewForCardInvoice = view('admin.work_cards.customers.ajax_customer',compact('customers','customer'))->render();
        return response()->json(
            [
                'customerDataViewForCardInvoice' => $customerDataViewForCardInvoice,
                'customerData' => $customerData,
                'customerId' => $customer->id,
                'customerName' => $customer->name,
                'customerAddress' => $customer->address,
                'customerPhone' => $customer->phone1 ?? $customer->phone2,
                'customerType' => $customer->name,
                'carsCount' => $customer->cars()->count(),
            ]
        );
    }

    public function validationRules(){

        $rules = [
            'city_id'=>'exists:cities,id',
            'customer_category_id'=>'nullable|integer|exists:customer_categories,id',
            'type'=>'required|string|in:person,company',
            'status'=>'required|in:1,0',
            'cars_number'=>'required|numeric|min:0',
            'balance_to'=>'nullable|numeric|min:0',
            'balance_for'=>'nullable|numeric|min:0',
        ];

        if(authIsSuperAdmin()){

            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch = request()->branch_id;

        }else{

            $branch = auth()->user()->branch_id;
        }

        $rules['name_en'] =
            [
                'required','string','max:150',
                Rule::unique('customers')->where(function ($query) use($branch){
                    return $query->where('name_en', request()->name_en)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['name_ar'] =
            [
                'required','string','max:150',
                Rule::unique('customers')->where(function ($query) use($branch){
                    return $query->where('name_ar', request()->name_ar)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['email'] =
            [
                'nullable','string','max:150',
                Rule::unique('customers')->where(function ($query) use($branch){
                    return $query->where('email', request()->email)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        return $rules;
    }

    public function customerBalance(Request $request){

        $customer = Customer::findOrFail($request['customer_id']);

        $data['customer_group_discount'] = 0;

        if($customer->customerCategory) {
            $data['customer_group_discount'] = $customer->customerCategory->sales_discount;
            $data['customer_discount_type'] = $customer->customerCategory->sales_discount_type;
        }

        $b = $customer->direct_balance();
        $data['funds_for'] = $b['debit'];
        $data['funds_on'] = $b['credit'];

        return $data;
    }

    public function dataByBranch(Request $request){

        $parts = Part::whereHas('store', function ($q) use($request){
            $q->where('branch_id', $request['branch_id']);
        })->get();

        $partsTypes = SparePart::where('branch_id', $request['branch_id'])->get();

        return view('admin.sales-invoices.parts.data_by_branches',compact('parts','partsTypes'));
    }

    public function partDetails(Request $request){

        $items_count = $request['items_count'] + 1;
        $parts_count = $request['parts_count'] + 1;

        $part = Part::where(function ($q) use ($request){

            $q->orWhere('id',$request['id']);

            if($request['barcode'])
                $q->orWhere('barcode',$request['barcode']);

        })->first();

       return view('admin.sales-invoices.parts.part_details',compact('part','items_count','parts_count'));
    }

    public function purchaseInvoiceData(Request $request){

        $invoice = PurchaseInvoice::findOrFail($request['invoice_id']);

        $invoice_item = $invoice->items()->where('part_id',$request['part_id'])->first();

        $part = Part::findOrFail($request['part_id']);

        $index = $request['index'];

        return view('admin.sales-invoices.parts.purchase_invoice_data',
            compact('invoice_item','part','invoice','index'));
    }

    public function customerPointsRules (Request $request) {

        try {

            $customer = Customer::find($request['customer_id']);

            $branch = $customer->branch;

            $pointsRules = PointRule::where('status', 1)->where('branch_id', $branch->id)->where('points', '<=', $customer->points)->get();

            $view = view('admin.sales-invoices.points.ajax_rules', compact('pointsRules'))->render();

        }catch (\Exception $e) {

            return response()->json( __('sorry, please try later'), 400);
        }

        return response()->json(['view'=> $view, 'customer_points' => $customer->points], 200);
    }
}
