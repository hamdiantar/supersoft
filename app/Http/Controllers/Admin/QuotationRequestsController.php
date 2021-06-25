<?php

namespace App\Http\Controllers\Admin;

use App\Models\Quotation;
use App\Services\MailServices;
use App\Services\NotificationServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\QuotationsRequests;

class QuotationRequestsController extends Controller
{
    use NotificationServices, MailServices;

    public function index(Request $request)
    {

        if (!auth()->user()->can('view_quotation_request')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $quotations = Quotation::whereIn('status', ['pending','rejected']);

        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'quotation-number' => 'quotation_number',
                'customer-name' => 'customer_id',
                'customer-phone' => 'customer_id',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at'
            ];
            $quotations = $quotations->orderBy($sort_fields[$sort_by] ,$sort_method);
        } else {
            $quotations = $quotations->orderBy('id', 'DESC');
        }
        if ($request->has('key')) {
            $key = $request->key;
            $quotations->where(function ($q) use ($key) {
                $q->where('quotation_number' ,'like' ,"%$key%")
                ->orWhere('created_at' ,'like' ,"%$key%")
                ->orWhere('updated_at' ,'like' ,"%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker ,['print' ,'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new QuotationsRequests($quotations ,$visible_columns), $request->invoker))();
        }
        $rows = $request->has('rows') ? $request->rows : 10;

        $quotations = $quotations->paginate($rows)->appends(request()->query());
        return view('admin.quotation_requests.index', compact('quotations'));
    }

    public function accept (Request $request) {

        if (!auth()->user()->can('accept_quotation_request')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {

            $quotation = Quotation::findOrFail($request['quotation_id']);

            $quotation->status = 'approved';

            $quotation->save();

        }catch (\Exception $e) {

            return redirect()->back()
                ->with(['message' => __('sorry, something went Wrong'), 'alert-type' => 'error']);
        }

        try {

            $this->sendNotification('quotation_request_status','customer',
                [
                    'quotation' => $quotation,
                    'message'=> 'Your quotation request is accepted'
                ]);

            if ($quotation->customer && $quotation->customer->email) {

                $this->sendMail($quotation->customer->email,'quotation_request_status','quotation_request_accept','App\Mail\QuotationRequest');
            }

        }catch (\Exception $e) {

            return redirect()->back()
                ->with(['message' => __('request approved successfully'), 'alert-type' => 'success']);
        }

        return redirect()->back()
            ->with(['message' => __('request approved successfully'), 'alert-type' => 'success']);

    }

    public function reject (Request $request) {

        if (!auth()->user()->can('reject_quotation_request')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $this->validate($request, [
            'rejected_reason'=>'string|max:300',
        ]);

        try {

            $quotation = Quotation::findOrFail($request['rejected_quotation_id']);

            $quotation->status = 'rejected';
            $quotation->rejected_reason = $request['reject_reason'];

            $quotation->save();

        }catch (\Exception $e) {

            return redirect()->back()
                ->with(['message' => __('sorry, something went Wrong'), 'alert-type' => 'error']);
        }

        try {

            $this->sendNotification('quotation_request_status','customer',
                [
                    'quotation' => $quotation,
                    'message'=> 'Your quotation request is rejected'
                ]);


            if ($quotation->customer && $quotation->customer->email) {

                $this->sendMail($quotation->customer->email,'quotation_request_status','quotation_request_reject','App\Mail\QuotationRequest');
            }

        }catch (\Exception $e) {

            return redirect()->back()
                ->with(['message' => __('request rejected successfully'), 'alert-type' => 'success']);
        }

        return redirect()->back()
            ->with(['message' => __('request rejected successfully'), 'alert-type' => 'success']);

    }
}
