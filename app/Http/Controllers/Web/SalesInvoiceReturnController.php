<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\TaxesFees;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use App\Models\SalesInvoiceReturn;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\CustomerPanel\SalesReturn;

class SalesInvoiceReturnController extends Controller
{
    public function index(Request $request){

        $auth = auth()->guard('customer')->user();

        $invoices = SalesInvoiceReturn::query()->where('customer_id', $auth->id);

        if($request->has('invoice_number') && $request['invoice_number'] != '')
            $invoices->where('id','like', $request['invoice_number']);

        if($request->has('type') && $request['type'] != '')
            $invoices->where('type',$request['type']);

        if($request->has('date_from') && $request['date_from'] != '')
            $invoices->whereDate('created_at','>=',$request['date_from']);

        if($request->has('date_to') && $request['date_to'] != '')
            $invoices->whereDate('created_at','<=',$request['date_to']);

        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'invoice-number' => 'invoice_number',
                'customer' => 'customer_id',
                'invoice-type' => 'type',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at'
            ];
            $invoices = $invoices->orderBy($sort_fields[$sort_by] ,$sort_method);
        } else {
            $invoices = $invoices->orderBy('id', 'DESC');
        }if ($request->has('key')) {
            $key = $request->key;
            $invoices->where(function ($q) use ($key) {
                $q->where('invoice_number' ,'like' ,"%$key%")
                ->orWhere('created_at' ,'like' ,"%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker ,['print' ,'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new SalesReturn($invoices->with('customer') ,$visible_columns), $request->invoker))();
        }
        $rows = $request->has('rows') ? $request->rows : 10;

        $invoices = $invoices->paginate($rows)->appends(request()->query());


        $salesInvoices = SalesInvoiceReturn::where('customer_id', $auth->id)->select('id','invoice_number')->get();
//        $salesInvoicesNumber = SalesInvoice::has('salesInvoiceReturn')->where('customer_id', $auth->id)->select('id','invoice_number')->get();

        return view('web.sales_invoice_return.index', compact('invoices','salesInvoices'));
    }

    public function show(Request $request)
    {
        $invoice = SalesInvoiceReturn::find($request->invoiceID);

        $taxes = TaxesFees::where('active_invoices', 1)->where('branch_id', $invoice->branch_id)->get();

        $totalTax =  TaxesFees::where('active_invoices', 1)->where('branch_id', $invoice->branch_id)->sum('value');

        $invoiceData =  view('web.sales_invoice_return.show',compact('invoice', 'taxes', 'totalTax'))->render();

        return response()->json(['invoice' => $invoiceData]);
    }
}
