<?php

namespace App\Http\Controllers\Admin;

use App\Models\Part;
use App\Models\PointRule;
use App\Models\User;
use App\Models\Branch;
use App\Models\Setting;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\SparePart;
use App\Models\TaxesFees;
use App\Notifications\LessPartsNotifications;
use App\Services\MailServices;
use App\Services\NotificationServices;
use App\Services\PointsServices;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use App\Models\RevenueReceipt;
use App\Models\PurchaseInvoice;
use App\Models\CustomerCategory;
use App\Models\SalesInvoiceItems;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Services\SampleSalesInvoiceServices;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\Invoices\Sales;
use App\Http\Requests\Admin\salesInvoice\CreateSalesInvoiceRequest;
use App\Http\Requests\Admin\salesInvoice\UpdateSalesInvoiceRequest;


class SalesInvoicesController extends Controller
{
    use  \App\Services\SalesInvoice, SampleSalesInvoiceServices, NotificationServices, MailServices, PointsServices;

    public function __construct()
    {
//        $this->middleware('permission:view_sales_invoices');
//        $this->middleware('permission:create_sales_invoices',['only'=>['create','store']]);
//        $this->middleware('permission:update_sales_invoices',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_sales_invoices',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_sales_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $invoices = SalesInvoice::query();
        $invoices->globalCheck($request);

        if ($request->has('invoice_number') && $request['invoice_number'] != '')
            $invoices->where('id', 'like', $request['invoice_number']);

        if ($request->has('customer_id') && $request['customer_id'] != '')
            $invoices->where('customer_id', $request['customer_id']);

        if ($request->has('customer_phone') && $request['customer_phone'] != '')
            $invoices->where('customer_id', $request['customer_phone']);

        if ($request->has('type') && $request['type'] != '')
            $invoices->where('type', $request['type']);

        if ($request->has('branch_id') && $request['branch_id'] != '')
            $invoices->where('branch_id', $request['branch_id']);

        if ($request->has('created_by') && $request['created_by'] != '')
            $invoices->where('created_by', $request['created_by']);

        if ($request->has('date_from') && $request['date_from'] != '')
            $invoices->whereDate('created_at', '>=', $request['date_from']);

        if ($request->has('date_to') && $request['date_to'] != '')
            $invoices->whereDate('created_at', '<=', $request['date_to']);

        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method : 'asc';
            if (!in_array($sort_method, ['asc', 'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'invoice-number' => 'invoice_number',
                'customer' => 'customer_id',
                'invoice-type' => 'type',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at'
            ];
            $invoices = $invoices->orderBy($sort_fields[$sort_by], $sort_method);
        } else {
            $invoices = $invoices->orderBy('id', 'DESC');
        }
        if ($request->has('key')) {
            $key = $request->key;
            $invoices->where(function ($q) use ($key) {
                $q->where('invoice_number', 'like', "%$key%")
                    ->orWhere('created_at', 'like', "%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker, ['print', 'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new Sales($invoices->with('customer'), $visible_columns), $request->invoker))();
        }
        $rows = $request->has('rows') ? $request->rows : 10;

        $invoices = $invoices->paginate($rows)->appends(request()->query());


        $users = filterSetting() ? User::all()->pluck('name', 'id') : null;
        $customers = filterSetting() ? Customer::get() : null;
        $branches = filterSetting() ? Branch::all()->pluck('name', 'id') : null;
        $salesInvoices = filterSetting() ? SalesInvoice::select('id', 'invoice_number')->get() : null;

        return view('admin.sales-invoices.index',
            compact('invoices', 'users', 'customers', 'branches', 'salesInvoices'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('create_sales_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::where('status', 1)->get()->pluck('name', 'id');

        $customers = Customer::where('status', 1);
        $customer_categories = CustomerCategory::where('status', 1);
        $parts = Part::where('status', 1);
        $partsTypes = SparePart::where('status', 1);
        $taxes = TaxesFees::where('active_invoices', 1);

        if ($request->has('branch_id') && $request['branch_id'] != null) {

            $customers->where('branch_id', $request['branch_id']);

            $customer_categories->where('branch_id', $request['branch_id']);

            $parts->whereHas('store', function ($q) use ($request) {
                $q->where('branch_id', $request['branch_id']);
            });

            $partsTypes->where('branch_id', $request['branch_id']);
            $taxes->where('branch_id', $request['branch_id']);
        }

        $customers = $customers->get();
        $customer_categories = $customer_categories->get();
        $parts = $parts->get();
        $partsTypes = $partsTypes->get();
        $taxes = $taxes->get();

        return view('admin.sales-invoices.create', compact('branches', 'customers', 'parts',
            'partsTypes', 'taxes', 'customer_categories'));
    }

    public function store(CreateSalesInvoiceRequest $request)
    {
        if (!auth()->user()->can('create_sales_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

//        dd($request->all());

        try {

            DB::beginTransaction();

            $data = $request->all();

            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }

            $invoice_data = $this->prepareInvoiceData($data);

            $invoice_data['created_by'] = auth()->id();

            if (!authIsSuperAdmin()) {

                $data['branch_id'] = auth()->user()->branch_id;
                $invoice_data['branch_id'] = auth()->user()->branch_id;

            } else {
                $invoice_data['branch_id'] = $request['branch_id'];
            }

            $setting_sell_From_invoice_status = $this->settingSellFromInvoiceStatus($invoice_data['branch_id']);

            $last_invoice = SalesInvoice::where('branch_id', $invoice_data['branch_id'])->latest('created_at')->first();

            $invoice_data['invoice_number'] = $last_invoice ? $last_invoice->invoice_number + 1 : 1;

            $sales_invoice = SalesInvoice::create($invoice_data);

            $this->handlePointsLog($sales_invoice);

            foreach ($request['part_ids'] as $index => $part_id) {

                $part = Part::find($part_id);

                $purchase_invoice = $this->getPurchaseInvoice($setting_sell_From_invoice_status, $invoice_data['branch_id'],
                    $data['purchase_invoice_id'][$index], $part_id);

                if (!$purchase_invoice) {
                    return redirect()->back()->with(['message' => __('words.something-wrong'), 'alert-type' => 'error']);
                }

                $invoice_item = $purchase_invoice->items()->where('part_id', $part_id)->first();

//                $itemsQty = $this->requestedPartQty($part, $data['sold_qty'][$index], $itemsQty);

                if ($part->quantity < $request['sold_qty'][$index]) {
                    return redirect()->back()->with(['message' => __('words.unavailable-qnt')]);
                }

//              REPEAT PART ITEM
                if ($invoice_item->purchase_qty < $data['sold_qty'][$index]) {

                    $next_purchase_invoices = $this->getNextPurchaseInvoices($setting_sell_From_invoice_status, $invoice_data['branch_id'],
                        $purchase_invoice->id, $part_id);

                    $this->repeatPartItem($data, $next_purchase_invoices, $index, $part_id, $sales_invoice);

                    continue;
                }

                $item_data = $this->calculateItemTotal($data, $index, $part_id, $purchase_invoice);

                $item_data['sales_invoice_id'] = $sales_invoice->id;
                $item_data['available_qty'] = $invoice_item->purchase_qty;

                $item = SalesInvoiceItems::create($item_data);

                $this->affectedPurchaseItem($invoice_item, $request['sold_qty'][$index]);

                $this->affectedPart($part_id, $request['sold_qty'][$index], $request['selling_price'][$index]);
            }

            $sales_invoice->number_of_items = $sales_invoice->items->count();
            $sales_invoice->save();

            DB::commit();
        } catch (\Exception $e) {

            DB::rollBack();

//            dd($e->getMessage());
            return redirect()->back()->with(['message' => __('words.something-wrong')]);
        }

        $url = route('admin:revenueReceipts.create', ['sales_invoice_id' => $sales_invoice->id]);

        try {

            $this->sendNotification('sales_invoice', 'customer',
                [
                    'sales_invoice' => $sales_invoice,
                    'message' => 'New sales invoice created for you please check'
                ]);

            if ($sales_invoice->customer && $sales_invoice->customer->email) {

                $this->sendMail($sales_invoice->customer->email, 'sales_invoice_status', 'sales_invoice_create', 'App\Mail\SalesInvoice');
            }

        } catch (\Exception $e) {

            return redirect($url)->with(['message' => __('words.sale-invoice-created'), 'alert-type' => 'success']);
        }

        return redirect($url)->with(['message' => __('words.sale-invoice-created'), 'alert-type' => 'success']);
    }

    public function revenueReceipts(SalesInvoice $invoice)
    {
        return view('admin.sales-invoices.revenue_receipts.index', compact('invoice'));
    }

    public function edit(SalesInvoice $invoice)
    {

        if (!auth()->user()->can('update_sales_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::where('status', 1)->get()->pluck('name', 'id');

        $customers = Customer::where('status', 1)->where('branch_id', $invoice->branch_id)->get();

        $parts = Part::where('status', 1)->whereHas('store', function ($q) use ($invoice) {
            $q->where('branch_id', $invoice->branch_id);
        })->get();

        $partsTypes = SparePart::where('status', 1)->where('branch_id', $invoice->branch_id)->get();

        $taxes = TaxesFees::where('active_invoices', 1)->where('branch_id', $invoice->branch_id)->get();

        $pointsRule = PointRule::find($invoice->points_rule_id);

        $rulePoints = $pointsRule ? $pointsRule->points : 0;

        $customerPoints = $invoice->customer ? $invoice->customer->points + $rulePoints : 0;

        $branch = $invoice->branch;

        $pointsRules = PointRule::where('status', 1)->where('branch_id', $branch->id)->where('points', '<=', $customerPoints)->get();

        return view('admin.sales-invoices.edit',
            compact('branches', 'customers', 'parts', 'partsTypes', 'taxes', 'invoice', 'pointsRules'));
    }

    public function update(UpdateSalesInvoiceRequest $request, SalesInvoice $invoice)
    {

        if (!auth()->user()->can('update_sales_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($invoice->RevenueReceipts->count()) {
            return redirect()->back()->with(['message' => __('words.sale-invoice-paid'), 'alert-type' => 'error']);
        }

        if ($invoice->salesInvoiceReturn) {
            return redirect()->back()->with(['message' => __('words.sale-invoice-returned'), 'alert-type' => 'error']);
        }

        try {
            DB::beginTransaction();

            $sales_invoice = $invoice;

            $data = $request->all();

            $data['branch_id'] = $invoice->branch_id;

//          reset quantity data for parts and purchase item
            $this->resetSalesInvoiceQty($invoice);

            $data['customer_id'] = $invoice->customer_id;

            $invoice_data = $this->prepareInvoiceData($data);

            unset($invoice_data['customer_id']);
            unset($invoice_data['type']);

            $sales_invoice->update($invoice_data);

            $this->handlePointsLog($sales_invoice);

            $setting_sell_From_invoice_status = $this->settingSellFromInvoiceStatus($sales_invoice->branch_id);

            foreach ($request['part_ids'] as $index => $part_id) {

                $part = Part::find($part_id);

                $purchase_invoice = $this->getPurchaseInvoice($setting_sell_From_invoice_status, $sales_invoice->branch_id,
                    $request['purchase_invoice_id'][$index], $part_id);

                if (!$purchase_invoice) {
                    return redirect()->back()->with(['message' => __('words.something-wrong')]);
                }

                $invoice_item = $purchase_invoice->items()->where('part_id', $part_id)->first();

                if (!$invoice_item) {
                    return redirect()->back()->with(['message' => __('words.something-wrong')]);
                }

                if ($part->quantity < $request['sold_qty'][$index]) {

                    return redirect()->back()->with(['message' => __('words.unavailable-qnt')]);
                }


//              REPEAT PART ITEM
                if ($invoice_item->purchase_qty < $data['sold_qty'][$index]) {

                    $next_purchase_invoices = $this->getNextPurchaseInvoices($setting_sell_From_invoice_status, $sales_invoice->branch_id,
                        $purchase_invoice->id, $part_id);

                    $this->repeatPartItem($data, $next_purchase_invoices, $index, $part_id, $sales_invoice);

                    continue;
                }

                $item_data = $this->calculateItemTotal($data, $index, $part_id, $purchase_invoice);

                $item_data['sales_invoice_id'] = $sales_invoice->id;
                $item_data['available_qty'] = $invoice_item->purchase_qty;

                $item = SalesInvoiceItems::create($item_data);

                $this->affectedPurchaseItem($invoice_item, $data['sold_qty'][$index]);

                $this->affectedPart($part_id, $data['sold_qty'][$index], $data['selling_price'][$index]);
            }

            DB::commit();
        } catch (\Exception $e) {

            DB::rollBack();
//            dd($e->getMessage());
            return redirect()->back()->with(['message' => __('words.something-wrong')]);
        }

//        $url = route('admin:revenueReceipts.create',['sales_invoice_id'=> $sales_invoice->id]);

//        if($sales_invoice->remaining <= 0){
        $url = route('admin:sales.invoices.index');
//        }

        try {

            $this->sendNotification('sales_invoice', 'customer',
                [
                    'sales_invoice' => $sales_invoice,
                    'message' => 'Your sales invoice updated, please check'
                ]);

            if ($sales_invoice->customer && $sales_invoice->customer->email) {

                $this->sendMail($sales_invoice->customer->email, 'sales_invoice_status', 'sales_invoice_edit', 'App\Mail\SalesInvoice');
            }

        } catch (\Exception $e) {

            return redirect($url)->with(['message' => __('words.sale-invoice-updated'), 'alert-type' => 'success']);
        }

        return redirect($url)->with(['message' => __('words.sale-invoice-updated'), 'alert-type' => 'success']);
    }

    public function show(Request $request)
    {
        if (!auth()->user()->can('view_sales_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $sales_invoice = SalesInvoice::find($request->invoiceID);

        $taxes = TaxesFees::where('active_invoices', 1)->where('branch_id', $sales_invoice->branch_id)->get();

        $totalTax = TaxesFees::where('active_invoices', 1)->where('branch_id', $sales_invoice->branch_id)->sum('value');

        $setting = Setting::where('branch_id', auth()->user()->branch_id)->where('sales_invoice_status', 1)->first();

        $invoice = view('admin.sales-invoices.show',
            compact('sales_invoice', 'taxes', 'totalTax', 'setting'))->render();

        return response()->json(['invoice' => $invoice]);
    }

    public function destroy(SalesInvoice $invoice)
    {

        if (!auth()->user()->can('delete_sales_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $invoice->delete();

        $this->sendNotification('sales_invoice', 'customer',
            [
                'sales_invoice' => $invoice,
                'message' => 'Your sales invoice deleted, please check'
            ]);

        if ($invoice->customer && $invoice->customer->email) {

            $this->sendMail($invoice->customer->email, 'sales_invoice_status', 'sales_invoice_delete', 'App\Mail\SalesInvoice');
        }

        return redirect()->back()->with(['message' => __('words.sale-invoice-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_sales_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {
            if (isset($request->ids) && is_array($request->ids)) {
                SalesInvoice::whereIn('id', array_unique($request->ids))->delete();
                return redirect()->back()
                    ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => __('words.try-agian'), 'alert-type' => 'error']);
        }
        return redirect()->back()
            ->with(['message' => __('words.select-row-least'), 'alert-type' => 'error']);
    }
}
