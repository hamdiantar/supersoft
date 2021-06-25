<?php

namespace App\Http\Controllers\Admin;

use App\Models\Locker;
use App\Models\Account;
use App\Models\User;
use App\Models\WorkCard;
use App\Notifications\CustomerWorkCardStatusNotification;
use App\Notifications\WorkCardStatusNotification;
use App\Services\MailServices;
use App\Services\NotificationServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;
use App\Models\CardInvoice;
use App\Models\RevenueItem;
use App\Models\RevenueType;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use App\Model\PurchaseReturn;
use App\Models\RevenueReceipt;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Filters\RevenueReceiptFilter;
use App\Services\RevenueReceiptsServices;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\RevenueReceipts;
use App\Http\Requests\Admin\RevenueReceipt\RevenueReceiptRequest;

class RevenueReceiptsController extends Controller
{
    use RevenueReceiptsServices, NotificationServices, MailServices;

    /**
     * @var RevenueReceiptFilter
     */
    protected $revenueReceiptFilter;

    public function __construct(RevenueReceiptFilter $revenueReceiptFilter)
    {
        $this->revenueReceiptFilter = $revenueReceiptFilter;
//        $this->middleware('permission:view_revenue_receipts');
//        $this->middleware('permission:create_revenue_receipts',['only'=>['create','store']]);
//        $this->middleware('permission:update_revenue_receipts',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_revenue_receipts',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {

        if (!auth()->user()->can('view_revenue_receipts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $revenueReceipts = RevenueReceipt::query();
        if ($request->hasAny((new RevenueReceipt())->getFillable())) {
            $revenueReceipts = $this->revenueReceiptFilter->filter($request);
        }
        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method : 'asc';
            if (!in_array($sort_method, ['asc', 'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'revenue-no' => 'id',
                'receiver' => 'receiver',
                'revenue-item' => 'revenue_item_id',
                'cost' => 'cost',
                'deportation-method' => 'deportation',
                'deportation' => 'deportation',
                'payment-type' => 'payment_type',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at'
            ];
            $revenueReceipts = $revenueReceipts->orderBy($sort_fields[$sort_by], $sort_method);
        } else {
            $revenueReceipts = $revenueReceipts->orderBy('id', 'DESC');
        }
        if ($request->has('key')) {
            $key = $request->key;
            $revenueReceipts->where(function ($q) use ($key) {
                $q->where('id', 'like', "%$key%")
                    ->orWhere('receiver', 'like', "%$key%")
                    ->orWhere('cost', 'like', "%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker, ['print', 'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (
            new ExportPrinterFactory(
                new RevenueReceipts($revenueReceipts->with(['revenueItem', 'locker', 'bank']), $visible_columns),
                $request->invoker
            )
            )();
        }
        $rows = $request->has('rows') ? $request->rows : 10;
        return view('admin.revenueReceipts.index', ['revenueReceipts' => $revenueReceipts->paginate($rows)->appends(request()->query())]);
    }

    public function create(Request $request)
    {

        if (!auth()->user()->can('create_revenue_receipts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($request->has('purchase_return_id') && $request->purchase_return_id !== null) {
            $invoice = PurchaseReturn::with('supplier')->find($request->purchase_return_id);

            $lockers = Locker::where('status', 1)->where('branch_id', $invoice->branch_id)->get();
            $accounts = Account::where('status', 1)->where('branch_id', $invoice->branch_id)->get();

            return view('admin.revenueReceipts.create', compact('invoice', 'lockers', 'accounts'));
        }

        if ($request->has('sales_invoice_id') && $request->sales_invoice_id !== null) {
            return $this->createFormForSalesInvoice($request);
        }

        if ($request->has('card_invoice_id') && $request->card_invoice_id !== null) {

            return $this->createForCardInvoice($request);
        }

        $lockers = Locker::where('status', 1)->get();
        $accounts = Account::where('status', 1)->get();

        return view('admin.revenueReceipts.create', compact('lockers', 'accounts'));
    }

    public function store(RevenueReceiptRequest $request)
    {

        if (!auth()->user()->can('create_revenue_receipts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();

        $url = 'admin/revenueReceipts';

        if ($request->has('sales_invoice_id') && $request['sales_invoice_id'] != null) {

            $invoice = SalesInvoice::find($request['sales_invoice_id']);

            $paid = $invoice->RevenueReceipts->sum('cost');
            $unPaid = $invoice->total - $paid;

            if ($unPaid < $request['cost']) {

                return redirect()->back()
                    ->with(['message' => __('words.cost-more-required'), 'alert-type' => 'error']);
            }

            if ($invoice->type == 'cash' && $invoice->total != $request['cost']) {
                return redirect()->back()
                    ->with(['message' => __('words.cost-not-equal-invoice'), 'alert-type' => 'error']);
            }

            $url = 'admin/sales-invoices-revenue-receipts/' . $invoice->id;

            if ($request['save_type'] == 'save_and_print') {

                $url = 'admin/sales-invoices/?print_type=print&invoice=' . $invoice->id;
            }

            $this->sendNotification('sales_invoice_payments', 'customer',
                [
                    'sales_invoice' => $invoice,
                    'message' => 'new payments to your sales invoice, please check'
                ]);

            if ($invoice->customer && $invoice->customer->email) {

                $this->sendMail($invoice->customer->email, 'sales_invoice_payments_status', 'sales_invoice_payments_create',
                    'App\Mail\SalesInvoicePayments');
            }
        }

        if ($request->has('card_invoice_id') && $request['card_invoice_id'] != null) {

            $invoice = CardInvoice::find($request['card_invoice_id']);

            if (number_format($invoice->remaining, 2) < number_format($request['cost'], 2)) {

                return redirect()->back()->with(['message' => __('words.cost-more-required'), 'alert-type' => 'error']);
            }

            if ($invoice->type == 'cash' && number_format($invoice->total) != number_format($request['cost'])) {

                return redirect()->back()->with(['message' => __('words.cost-not-equal-invoice'), 'alert-type' => 'error']);
            }

            if ($invoice->remaining <= $request['cost'] && $invoice->workCard) {

                if ($invoice->workCard->status != 'scheduled') {

                    $invoice->workCard->status = 'finished';
                    $invoice->workCard->save();
                }

                $this->sendNotification('work_card_status_to_customer', 'customer',
                    [
                        'work_card' => $invoice->workCard,
                        'message' => 'Your Work Card Updated, With Status Finished'
                    ]);

                $this->sendNotification('work_card_status_to_user', 'admins',
                    [
                        'work_card' => $invoice->workCard,
                        'message' => 'Work Card Status  updated to Finished'
                    ]);
            }

            if ($invoice->workCard->customer && $invoice->workCard->customer->email) {

                $this->sendMail($invoice->workCard->customer->email, 'work_card_payments_status', 'work_card_payments_create',
                    'App\Mail\WorkCardPayments');
            }

            $this->sendNotification('work_card_payments', 'customer',
                [
                    'work_card' => $invoice->workCard,
                    'message' => 'new payments to your work card, please check'
                ]);


            $url = 'admin/card-invoices-revenue-receipts/' . $invoice->id;

            if ($request['save_type'] == 'save_and_print') {

                $url = 'admin/work-cards/?print_type=print&invoice=' . $invoice->id;
            }

        }

        if ($request->has('purchase_return_id') && $request['purchase_return_id'] != null) {

            $invoice = PurchaseReturn::find($request['purchase_return_id']);

            $url = 'admin/purchase-returns/revenues/' . $invoice->id;

            if ($request['save_type'] == 'save_and_print') {

                $url = 'admin/purchase-returns/?print_type=print&invoice=' . $invoice->id;
            }

            if ($invoice->type === 'cash' && $request->cost > $invoice->total_after_discount) {
                return redirect()->back()
                    ->with(['message' => __('words.paid-more-than-total'), 'alert-type' => 'error']);
            }

            if ($invoice->type === 'cash') {

                $getOldPaidCost = RevenueReceipt::where('purchase_return_id', $invoice->id)->sum('cost');

                $totalPaid = $getOldPaidCost + $request->cost;

                if ($totalPaid > $invoice->total_after_discount) {
                    return redirect()->back()
                        ->with(['message' => __('words.paid-more-than-total'), 'alert-type' => 'error']);
                }
                $invoice->update([
                    'paid' => $totalPaid,
                    'remaining' => $invoice->total_after_discount - $totalPaid,
                ]);
            }

            if ($invoice->type === 'credit') {

                $getOldPaidCost = RevenueReceipt::where('purchase_return_id', $invoice->id)->sum('cost');

                $totalPaid = $getOldPaidCost + $request->cost;

                if ($totalPaid > $invoice->total_after_discount) {
                    return redirect()->back()
                        ->with(['message' => __('words.paid-more-than-total'), 'alert-type' => 'error']);
                }

                $invoice->update([
                    'paid' => $totalPaid,
                    'remaining' => $invoice->total_after_discount - $totalPaid,
                ]);
            }
        }

        $data['branch_id'] = auth()->user()->branch_id;

        if (authIsSuperAdmin()) {
            $data['branch_id'] = $request['branch_id'];
        }

        $data['number'] = generateNumber($data['branch_id'], "App\Models\RevenueReceipt", 'number');

        $revenueReceipt = RevenueReceipt::create($data);

        if ($request->has('sales_invoice_id') && $request['sales_invoice_id'] != null) {

            if ($request['save_type'] == 'save_and_print_receipt') {

                $url = 'admin/sales-invoices-revenue-receipts/' . $invoice->id . '#show' . $revenueReceipt->id;
            }
        }

        if ($request->has('card_invoice_id') && $request['card_invoice_id'] != null) {

            if ($request['save_type'] == 'save_and_print_receipt') {

                $url = 'admin/card-invoices-revenue-receipts/' . $invoice->id . '#show' . $revenueReceipt->id;
            }
        }

        if ($request->has('purchase_return_id') && $request['purchase_return_id'] != null) {

            if ($request['save_type'] == 'save_and_print_receipt') {

                $url = 'admin/purchase-returns/revenues/' . $invoice->id . '#show' . $revenueReceipt->id;
            }
        }

        if ($request['save_type'] == 'save_and_print_receipt' && !$request->has('sales_invoice_id')
            && !$request->has('card_invoice_id') && !$request->has('purchase_return_id')) {

            $url = 'admin/revenueReceipts#show' . $revenueReceipt->id;
        }

        if ($request->has('card_invoice_id') && $request['card_invoice_id'] != null) {

            $invoice = CardInvoice::find($request['card_invoice_id']);

            if ($invoice->remaining <= 0 && $invoice->workCard) {

                $invoice->workCard->status = 'finished';
                $invoice->workCard->save();
            }
        }

        if ($revenueReceipt && $request->has('locker_id')) {
            $this->addBalanceInLocker($request->all());
            return redirect()->to($url)
                ->with(['message' => __('words.revenue-receipt-created'), 'alert-type' => 'success']);
        }

        if ($revenueReceipt && $request->has('account_id')) {
            $this->addBalanceInBankAccount($request->all());
            return redirect()->to($url)
                ->with(['message' => __('words.revenue-receipt-created'), 'alert-type' => 'success']);
        }

    }

    public function edit(RevenueReceipt $revenueReceipt)
    {
        if (!auth()->user()->can('update_revenue_receipts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($revenueReceipt->purchase_return_id) {
            $invoice = PurchaseReturn::find($revenueReceipt->purchase_return_id);
            $expenses = RevenueReceipt::where('purchase_return_id', $invoice->id);
            $expenseSum = RevenueReceipt::where('purchase_return_id', $invoice->id)->sum('cost');
            $remaining = $invoice->total_after_discount - $expenseSum;

            $lockers = Locker::where('status', 1)->where('branch_id', $invoice->branch_id)->get();
            $accounts = Account::where('status', 1)->where('branch_id', $invoice->branch_id)->get();

            return view('admin.revenueReceipts.edit',
                compact('revenueReceipt', 'invoice', 'lockers', 'accounts'));
        }

        if ($revenueReceipt->sales_invoice_id) {
            return $this->editFormForSalesInvoice($revenueReceipt);
        }

        if ($revenueReceipt->card_invoice_id) {
            return $this->editFormForCardInvoice($revenueReceipt);
        }

        $lockers = Locker::where('status', 1)->get();
        $accounts = Account::where('status', 1)->get();

        return view('admin.revenueReceipts.edit', compact('revenueReceipt', 'lockers', 'accounts'));
    }

    public function update(RevenueReceiptRequest $request, RevenueReceipt $revenueReceipt)
    {

        if (!auth()->user()->can('update_revenue_receipts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {

            $url = 'admin/revenueReceipts';

            if ($request['save_type'] == 'save_and_print_receipt') {

                $url = 'admin/revenueReceipts#show' . $revenueReceipt->id;
            }

            if ($revenueReceipt->purchase_return_id) {

                $invoice = PurchaseReturn::find($revenueReceipt->purchase_return_id);

                $url = 'admin/purchase-returns/revenues/' . $invoice->id;

                if ($request['save_type'] == 'save_and_print') {

                    $url = 'admin/purchase-returns/?print_type=print&invoice=' . $invoice->id;
                }

                if ($request['save_type'] == 'save_and_print_receipt') {

                    $url = 'admin/purchase-returns/revenues/' . $invoice->id . '#show' . $revenueReceipt->id;
                }

                if ($invoice->type === 'credit') {

                    $getOldPaidCost = RevenueReceipt::where('purchase_return_id', $invoice->id)->sum('cost');

                    $totalPaid = ($getOldPaidCost - $revenueReceipt->cost) + $request['cost'];

                    if ($totalPaid > $invoice->total_after_discount) {
                        return redirect()->back()
                            ->with(['message' => __('words.paid-more-than-total'), 'alert-type' => 'error']);
                    }

                    $invoice->update([
                        'paid' => $totalPaid,
                        'remaining' => $invoice->total_after_discount - $totalPaid,
                    ]);
                }
            }

            if ($revenueReceipt->sales_invoice_id) {

                $invoice = SalesInvoice::find($revenueReceipt->sales_invoice_id);

                $total_amount = ($invoice->paid - $revenueReceipt->cost) + $request['cost'];

                if ($total_amount > $invoice->total) {

                    return redirect()->back()
                        ->with(['message' => __('words.cost-more-required'), 'alert-type' => 'error']);
                }

                if ($invoice->type == 'cash' && $invoice->total != $request['cost']) {
                    return redirect()->back()
                        ->with(['message' => __('words.cost-not-equal-invoice'), 'alert-type' => 'error']);
                }

                $url = 'admin/sales-invoices-revenue-receipts/' . $invoice->id;

                if ($request['save_type'] == 'save_and_print') {

                    $url = 'admin/sales-invoices/?print_type=print&invoice=' . $invoice->id;
                }

                if ($request['save_type'] == 'save_and_print_receipt') {

                    $url = 'admin/sales-invoices-revenue-receipts/' . $invoice->id . '#show' . $revenueReceipt->id;
                }

                $this->sendNotification('sales_invoice_payments', 'customer',
                    [
                        'sales_invoice' => $invoice,
                        'message' => 'Your payments to your sales invoice updated, please check'
                    ]);

                if ($invoice->customer && $invoice->customer->email) {

                    $this->sendMail($invoice->customer->email, 'sales_invoice_payments_status', 'sales_invoice_payments_edit',
                        'App\Mail\SalesInvoicePayments');
                }
            }

            if ($revenueReceipt->card_invoice_id) {

                $invoice = CardInvoice::find($revenueReceipt->card_invoice_id);

                $total_amount = ($invoice->paid - $revenueReceipt->cost) + $request['cost'];

                if ($total_amount > $invoice->total) {

                    return redirect()->back()
                        ->with(['message' => __('words.cost-more-required'), 'alert-type' => 'error']);
                }

                if ($invoice->type == 'cash' && $invoice->total != $request['cost']) {
                    return redirect()->back()
                        ->with(['message' => __('words.cost-not-equal-invoice'), 'alert-type' => 'error']);
                }

                $url = 'admin/card-invoices-revenue-receipts/' . $invoice->id;

                if ($request['save_type'] == 'save_and_print') {

                    $url = 'admin/work-cards/?print_type=print&invoice=' . $invoice->id;
                }

                if ($request['save_type'] == 'save_and_print_receipt') {

                    $url = 'admin/card-invoices-revenue-receipts/' . $invoice->id . '#show' . $revenueReceipt->id;
                }

                $this->sendNotification('work_card_payments', 'customer',
                    [
                        'work_card' => $invoice->workCard,
                        'message' => 'payments to your work card updated, please check'
                    ]);

                if ($invoice->workCard->customer && $invoice->workCard->customer->email) {

                    $this->sendMail($invoice->workCard->customer->email, 'work_card_payments_status', 'work_card_payments_edit',
                        'App\Mail\WorkCardPayments');
                }
            }

            $data = $request->only(['time', 'date', 'revenue_type_id', 'revenue_item_id', 'for', 'receiver', 'payment_type',
                'bank_name', 'check_number', 'deportation', 'user_account_type', 'user_account_id', 'cost_center_id']);

            $revenueReceipt->update($data);

//            if (!$this->checkIfCanUpdateCost($revenueReceipt, $request['cost'])) {
//
//                return redirect()->back()->with(['message' => __('sorry, cost can not be updated'), 'alert-type' => 'info']);
//            }

//            DB::beginTransaction();
//
//            $this->reset($revenueReceipt);
//
//            $updateStatus = $this->updateReceiptCost($revenueReceipt, $request);
//
//            if (!$updateStatus) {
//
//                return redirect()->back()->with(['message' => __('sorry, cost can not be updated, please check'), 'alert-type' => 'error']);
//            }
//
//            DB::commit();

        } catch (\Exception $e) {

//            DB::rollBack();

            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }

        return redirect()->to($url)->with(['message' => __('words.revenue-receipt-created'), 'alert-type' => 'success']);


//        if ($request->has('locker_id') && $request->locker_id != $revenueReceipt->locker_id) {
//            $this->returnBalanceToSafe($revenueReceipt->locker_id, $revenueReceipt->cost);
//            $this->addBalanceInLocker($request->all());
//            $revenueReceipt->update($request->all());
//            return redirect()->to($url)
//                ->with(['message' => __('words.revenue-receipt-updated'), 'alert-type' => 'success']);
//        }
//
//        if ($request->has('account_id') && $request->account_id != $revenueReceipt->account_id) {
//            $this->returnBalanceToBankAccount($revenueReceipt->account_id, $revenueReceipt->cost);
//            $this->addBalanceInBankAccount($request->all());
//            $revenueReceipt->update($request->all());
//            return redirect()->to($url)
//                ->with(['message' => __('words.revenue-receipt-updated'), 'alert-type' => 'success']);
//        }
//
//        if ($request->cost == $revenueReceipt->cost && $request->has('locker_id')) {
//            $revenueReceipt->update($request->all());
//            return redirect()->to($url)
//                ->with(['message' => __('words.revenue-receipt-updated'), 'alert-type' => 'success']);
//        }
//
//        if ($request->cost == $revenueReceipt->cost && $request->has('account_id')) {
//            $revenueReceipt->update($request->all());
//            return redirect()->to($url)
//                ->with(['message' => __('words.revenue-receipt-updated'), 'alert-type' => 'success']);
//        }
//
//        $data = $request->all();
//
//
//        if ($request->cost > $revenueReceipt->cost && $request->has('locker_id')) {
//            $data['cost'] = $request->cost - $revenueReceipt->cost;
//            $this->addBalanceInLocker($data);
//            $revenueReceipt->update($request->all());
//            return redirect()->to($url)
//                ->with(['message' => __('words.revenue-receipt-updated'), 'alert-type' => 'success']);
//        }
//        if ($revenueReceipt->cost > $request->cost && $request->has('locker_id')) {
//            $data['cost'] = $revenueReceipt->cost - $request->cost;
//            $this->reduceBalanceInLocker($data);
//            $revenueReceipt->update($request->all());
//            return redirect()->to($url)
//                ->with(['message' => __('words.revenue-receipt-updated'), 'alert-type' => 'success']);
//        }
//        if ($request->cost > $revenueReceipt->cost && $request->has('account_id')) {
//            $data['cost'] = $request->cost - $revenueReceipt->cost;
//            $this->addBalanceInBankAccount($data);
//            $revenueReceipt->update($request->all());
//            return redirect()->to($url)
//                ->with(['message' => __('words.revenue-receipt-updated'), 'alert-type' => 'success']);
//        }
//        if ($revenueReceipt->cost > $request->cost && $request->has('account_id')) {
//            $data['cost'] = $revenueReceipt->cost - $request->cost;
//            $this->reduceBalanceInBankAccount($data);
//            $revenueReceipt->update($request->all());
//            return redirect()->to($url)
//                ->with(['message' => __('words.revenue-receipt-updated'), 'alert-type' => 'success']);
//        }
    }

    public function destroy(RevenueReceipt $revenueReceipt)
    {
        if (!auth()->user()->can('delete_revenue_receipts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $this->handelCustomerBalance($revenueReceipt);

        $url = "admin/revenueReceipts";

        if ($revenueReceipt->purchase_return_id !== null) {
            $url = 'admin/purchase-returns/revenues/' . $revenueReceipt->purchase_return_id;
        }

        if ($revenueReceipt->card_invoice_id !== null) {
            $url = 'admin/card-invoices-revenue-receipts/' . $revenueReceipt->card_invoice_id;
        }

        if ($revenueReceipt->salesInvoice) {

            $this->sendNotification('sales_invoice_payments', 'customer',
                [
                    'sales_invoice' => $revenueReceipt->salesInvoice,
                    'message' => 'Your payments to your sales invoice deleted, please check'
                ]);

            if ($revenueReceipt->salesInvoice->customer && $revenueReceipt->salesInvoice->customer->email) {

                $this->sendMail($revenueReceipt->salesInvoice->customer->email, 'sales_invoice_payments_status',
                    'sales_invoice_payments_delete', 'App\Mail\SalesInvoicePayments');
            }
        }

        if ($revenueReceipt->cardInvoice) {

            $this->sendNotification('work_card_payments', 'customer',
                [
                    'work_card' => $revenueReceipt->cardInvoice->workCard,
                    'message' => 'payments to your work card deleted, please check'
                ]);

            if ($revenueReceipt->cardInvoice->workCard->customer && $revenueReceipt->cardInvoice->workCard->customer->email) {

                $this->sendMail($revenueReceipt->cardInvoice->workCard->customer->email,
                    'work_card_payments_status', 'work_card_payments_delete', 'App\Mail\WorkCardPayments');
            }
        }

        if ($revenueReceipt->locker_id !== null) {

            $this->removeBalanceFromLocker($revenueReceipt);
            $revenueReceipt->delete();
            return redirect()->to($url)
                ->with(['message' => __('words.revenue-receipt-deleted'), 'alert-type' => 'success']);
        }

        if ($revenueReceipt->account_id !== null) {

            $this->removeBalanceFromAccount($revenueReceipt);
            $revenueReceipt->delete();
            return redirect()->to($url)
                ->with(['message' => __('words.revenue-receipt-deleted'), 'alert-type' => 'success']);
        }

    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_revenue_receipts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            $this->removeBulkBalance(RevenueReceipt::whereIn('id', $request->ids)->get());
            RevenueReceipt::deleteSelectedFromRestriction($request->ids);// remove restriction first becuase soft delete removed from revenue receipts
            RevenueReceipt::whereIn('id', $request->ids)->delete();
            return redirect()->back()
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->back()->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function removeBulkBalance(Collection $collection)
    {
        foreach ($collection as $col) {
            if ($col->locker_id !== null) {
                $this->removeBalanceFromLocker($col);
            }
            if ($col->account_id !== null) {
                $this->removeBalanceFromAccount($col);
            }

            $this->handelCustomerBalance($col);
        }
    }

    public function removeBalanceFromLocker(RevenueReceipt $receipt)
    {
        $locker = Locker::find($receipt->locker_id);
        if ($locker) {
            $locker->update([
                'balance' => ($locker->balance - $receipt->cost)
            ]);
        }
    }

    public function removeBalanceFromAccount(RevenueReceipt $receipt)
    {
        $account = Account::find($receipt->account_id);
        if ($account) {
            $account->update([
                'balance' => ($account->balance - $receipt->cost)
            ]);
        }
    }

    public function addBalanceInLocker(array $data)
    {
        $locker = Locker::find($data['locker_id']);
        if ($locker) {
            $locker->update([
                'balance' => ($locker->balance + $data['cost'])
            ]);
        }
    }

    public function addBalanceInBankAccount(array $data)
    {
        $account = Account::find($data['account_id']);
        if ($account) {
            $account->update([
                'balance' => ($account->balance + $data['cost'])
            ]);
        }
    }

    public function reduceBalanceInLocker(array $data)
    {
        $locker = Locker::find($data['locker_id']);
        if ($locker) {
            $locker->update([
                'balance' => ($locker->balance - $data['cost'])
            ]);
        }

    }

    public function reduceBalanceInBankAccount(array $data)
    {
        $account = Account::find($data['account_id']);
        if ($account) {
            $account->update([
                'balance' => ($account->balance + $data['cost'])
            ]);
        }

    }

    public function returnBalanceToSafe(int $lockerId, $balance)
    {
        $locker = Locker::find($lockerId);
        if ($locker) {
            $locker->update([
                'balance' => ($locker->balance + $balance)
            ]);
        }

    }

    public function returnBalanceToBankAccount(int $accountID, $balance)
    {
        $account = Account::find($accountID);
        if ($account) {
            $account->update([
                'balance' => ($account->balance + $balance)
            ]);
        }
    }

    public function getRevenueItemsByTypeId(Request $request)
    {
        $items = RevenueItem::where('revenue_id', $request->revenue_type_id)->get();

        $htmlItems = '<option value="">' . __('Select Revenue Item') . '</option>';
        foreach ($items as $item) {
            $htmlItems .= '<option value="' . $item->id . '">' . $item->item . '</option>';
        }
        return response()->json([
            'items' => $htmlItems,
        ]);
    }

    public function getRevenueNumbersByBranch(Request $request)
    {
        $numbers = RevenueReceipt::where('branch_id', $request->branch_id)->get();

        $htmlNumbers = ' ';
        foreach ($numbers as $number) {
            $htmlNumbers .= '<option value="' . $number->id . '">' . $number->id . '</option>';
        }
        return response()->json([
            'numbers' => $htmlNumbers,
        ]);
    }

    public function getRevenueReceiversByBranch(Request $request)
    {
        $receivers = RevenueReceipt::where('branch_id', $request->branch_id)->get();

        $htmlReceivers = '<option value="">' . __('Select Receiver') . '</option>';
        foreach ($receivers as $receiver) {
            $htmlReceivers .= '<option value="' . $receiver->receiver . '">' . $receiver->receiver . '</option>';
        }
        return response()->json([
            'receivers' => $htmlReceivers,
        ]);
    }

    public function handelCustomerBalance($revenueReceipt)
    {

//        if($revenueReceipt->sales_invoice_id){
//
//            $sales_invoice = $revenueReceipt->salesInvoice;
//
//            if($sales_invoice){
//                $customer = $sales_invoice->customer;
//
//                if($customer){
//                    $customer->balance_to += $revenueReceipt->cost;
//                    $customer->save();
//                }
//            }
//        }
    }
}
