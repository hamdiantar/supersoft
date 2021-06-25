<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use App\Services\MailServices;
use Illuminate\Http\Request;
use App\Models\CustomerRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\CustomerRequests;

class CustomerRequestController extends Controller
{
    use MailServices;

    public function index () {

        if (!auth()->user()->can('view_customer_request')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (request()->has('sort_by') && request('sort_by') != '') {
            $sort_by = request('sort_by');
            $sort_method = request()->has('sort_method') ? request('sort_method') :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'name' => 'name',
                'phone' => 'phone',
                'address' => 'address',
                'username' => 'username',
                'status' => 'status',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at'
            ];
            $customerRequests = CustomerRequest::orderBy($sort_fields[$sort_by] ,$sort_method);
        } else {
            $customerRequests = CustomerRequest::orderBy('id', 'DESC');
        }
        if (request()->has('key')) {
            $key = request('key');
            $customerRequests->where(function ($q) use ($key) {
                $q->where('name' ,'like' ,"%$key%")
                ->orWhere('phone' ,'like' ,"%$key%")
                ->orWhere('username' ,'like' ,"%$key%");
            });
        }
        if (request()->has('invoker') && in_array(request('invoker') ,['print' ,'excel'])) {
            $visible_columns = request()->has('visible_columns') ? request('visible_columns') : [];
            return (new ExportPrinterFactory(new CustomerRequests($customerRequests ,$visible_columns) ,request('invoker')))();
        }

        $rows = request()->has('rows') ? request('rows') : 10;
        $customerRequests = $customerRequests->paginate($rows)->appends(request()->query());
        return view('admin.customer_request.index', compact('customerRequests'));
    }

    public function Accept(Request $request) {

        if (!auth()->user()->can('accept_customer_request')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $this->validate($request, [

            'request_id' => 'required|integer|exists:customer_requests,id',
        ]);

        try {

            $customerRequest = CustomerRequest::find($request['request_id']);

            if ($customerRequest->status != 'pending') {

                return redirect()->back()
                    ->with(['message' => __('sorry, this request status not pending'), 'alert-type' => 'error']);

            }

            DB::beginTransaction();

            $customerRequest->status = 'approved';
            $customerRequest->save();

            $data = [

                'name_ar'       => $customerRequest->name,
                'name_en'       => $customerRequest->name,
                'phone1'        => $customerRequest->phone,
                'address'       => $customerRequest->address,
                'type'          => 'person',
                'branch_id'     => $customerRequest->branch_id,
                'username'      => $customerRequest->username,
                'password'      => $customerRequest->password,
                'email'         => $customerRequest->email,
                'provider'      => $customerRequest->provider,
            ];

            $customer = Customer::create($data);

            DB::commit();

        }catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()
                ->with(['message' => __('sorry, something went Wrong'), 'alert-type' => 'error']);
        }

        try {

            if ($customerRequest->email) {

                $this->sendMail($customerRequest->email,'customer_request_status','customer_request_accept','App\Mail\CustomerRequest');
            }

        }catch (\Exception $e) {

            return redirect()->back()
                ->with(['message' => __('request approved successfully'), 'alert-type' => 'success']);

        }
    }

    public function Reject(Request  $request) {

        if (!auth()->user()->can('reject_customer_request')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $this->validate($request, [

            'rejected_request_id' => 'required|integer|exists:customer_requests,id',
            'reject_reason' => 'nullable|string|max:500',
        ]);

        try {

            $customerRequest = CustomerRequest::find($request['rejected_request_id']);

            if ($customerRequest->status != 'pending') {

                return redirect()->back()
                    ->with(['message' => __('sorry, this request status not pending'), 'alert-type' => 'error']);

            }

            $customerRequest->status = 'rejected';
            $customerRequest->reject_reason = $request['reject_reason'];
            $customerRequest->save();

        }catch (\Exception $e) {

            return redirect()->back()
                ->with(['message' => __('sorry, something went Wrong'), 'alert-type' => 'error']);
        }

        try {

            if ($customerRequest->email) {

                $this->sendMail($customerRequest->email,'customer_request_status','customer_request_reject','App\Mail\CustomerRequest');
            }

        }catch (\Exception $e) {

            return redirect()->back()
                ->with(['message' => __('request rejected successfully'), 'alert-type' => 'success']);

        }

        return redirect()->back()
            ->with(['message' => __('request rejected successfully'), 'alert-type' => 'success']);
    }

    public function destroy (CustomerRequest  $customerRequest) {

        if (!auth()->user()->can('delete_customer_request')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $customerRequest->delete();

        return redirect()->back()->with(['message'=>'request deleted successfully', 'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_customer_request')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {

            CustomerRequest::whereIn('id', $request->ids)->delete();

            return redirect()->back()->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }

        return redirect()->back()->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

}
