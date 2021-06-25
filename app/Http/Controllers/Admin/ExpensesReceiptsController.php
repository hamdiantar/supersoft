<?php

namespace App\Http\Controllers\Admin;

use App\Models\Locker;
use App\Models\Account;
use App\Services\MailServices;
use App\Services\NotificationServices;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\ExpensesItem;
use Illuminate\Http\Request;
use App\Models\ExpensesReceipt;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoiceReturn;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Filters\ExpenseReceiptFilter;
use App\Services\ExpensesReceiptsServices;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\ExpenseReceipts;
use App\Http\Requests\Admin\ExpenseReceipt\ExpenseReceiptRequest;

class ExpensesReceiptsController extends Controller
{
    use ExpensesReceiptsServices, NotificationServices, MailServices;

    protected $expenseReceiptFilter;

    public function __construct(ExpenseReceiptFilter $expenseReceiptFilter)
    {
        $this->expenseReceiptFilter = $expenseReceiptFilter;
//        $this->middleware('permission:view_expense_receipts');
//        $this->middleware('permission:create_expense_receipts',['only'=>['create','store']]);
//        $this->middleware('permission:update_expense_receipts',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_expense_receipts',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {

        if (!auth()->user()->can('view_expense_receipts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $expenseReceipts = ExpensesReceipt::query();
        if ($request->hasAny((new ExpensesReceipt())->getFillable())) {
            $expenseReceipts = $this->expenseReceiptFilter->filter($request);
        }
        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method : 'asc';
            if (!in_array($sort_method, ['asc', 'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'expense-no' => 'id',
                'receiver' => 'receiver',
                'expense-item' => 'expense_item_id',
                'cost' => 'cost',
                'deportation-method' => 'deportation',
                'deportation' => 'deportation',
                'payment-type' => 'payment_type',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at'
            ];
            $expenseReceipts = $expenseReceipts->orderBy($sort_fields[$sort_by], $sort_method);
        } else {
            $expenseReceipts = $expenseReceipts->orderBy('id', 'DESC');
        }
        if ($request->has('key')) {
            $key = $request->key;
            $expenseReceipts->where(function ($q) use ($key) {
                $q->where('id', 'like', "%$key%")
                    ->orWhere('receiver', 'like', "%$key%")
                    ->orWhere('cost', 'like', "%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker, ['print', 'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (
            new ExportPrinterFactory(
                new ExpenseReceipts($expenseReceipts->with(['expenseItem', 'locker', 'bank']), $visible_columns),
                $request->invoker
            )
            )();
        }
        $rows = $request->has('rows') ? $request->rows : 10;
        return view('admin.expenseReceipts.index', ['expenseReceipts' => $expenseReceipts->paginate($rows)->appends(request()->query())]);
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('create_expense_receipts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($request->has('invoice_id') && $request->invoice_id !== null) {
            $invoice = PurchaseInvoice::find($request->invoice_id);
            $expenseSum = ExpensesReceipt::where('purchase_invoice_id', $invoice->id)->sum('cost');
            $remaining = $invoice->total_after_discount - $expenseSum;

            $lockers = Locker::where('status', 1)->where('branch_id', $invoice->branch_id)->get();
            $accounts = Account::where('status', 1)->where('branch_id', $invoice->branch_id)->get();

            return view('admin.expenseReceipts.create',
                compact('invoice', 'remaining', 'expenseSum', 'lockers', 'accounts'));
        }

        if ($request->has('sales_invoice_return_id') && $request['sales_invoice_return_id'] !== null) {
            return $this->createFormForSalesInvoiceReturn($request);
        }

        $lockers = Locker::where('status', 1)->get();
        $accounts = Account::where('status', 1)->get();

        return view('admin.expenseReceipts.create', compact('lockers', 'accounts'));
    }

    public function store(ExpenseReceiptRequest $request)
    {
        if (!auth()->user()->can('create_expense_receipts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        $url = 'admin/expenseReceipts';

        if ($request->has('sales_invoice_return_id') && $request['sales_invoice_return_id'] != null) {

            $invoice = SalesInvoiceReturn::find($request['sales_invoice_return_id']);

            if ($invoice->remaining < $request['cost']) {

                return redirect()->back()
                    ->with(['message' => __('words.cost-more-required'), 'alert-type' => 'error']);
            }

            if ($invoice->type == 'cash' && $invoice->total != $request['cost']) {
                return redirect()->back()
                    ->with(['message' => __('words.cost-not-equal-invoice'), 'alert-type' => 'error']);
            }

            $url = 'admin/sales-invoices-return/expenses-receipts/' . $invoice->id;

            if ($request['save_type'] == 'save_and_print') {

                $url = 'admin/sales-invoices-return/?print_type=print&invoice=' . $invoice->id;
            }

            $this->sendNotification('return_sales_invoice_payments', 'customer',
                [
                    'sales_invoice_return' => $invoice,
                    'message' => 'new payments to your sales invoice return, please check'
                ]);

            if ($invoice->customer && $invoice->customer->email) {

                $this->sendMail($invoice->customer->email, 'sales_return_payments_status', 'sales_return_payments_create',
                    'App\Mail\SalesInvoiceReturnPayments');
            }
        }

        if ($request->has('purchase_invoice_id') && $request['purchase_invoice_id'] != null) {

            $invoice = PurchaseInvoice::find($request['purchase_invoice_id']);

            $url = 'admin/purchase-invoices/expenses/' . $invoice->id;

            if ($request['save_type'] == 'save_and_print') {

                $url = 'admin/purchase-invoices/?print_type=print&invoice=' . $invoice->id;
            }

            if ($invoice->remaining < $request['cost']) {

                return redirect()->back()->with(['message' => __('words.cost-more-required'), 'alert-type' => 'error']);
            }
        }

        $data['branch_id'] = auth()->user()->branch_id;

        if (authIsSuperAdmin()) {
            $data['branch_id'] = $request['branch_id'];
        }

        $data['number'] = generateNumber($data['branch_id'], "App\Models\ExpensesReceipt", 'number');

        $expenseReceipt = ExpensesReceipt::create($data);

        if ($request->has('sales_invoice_return_id') && $request['sales_invoice_return_id'] != null) {

            if ($request['save_type'] == 'save_and_print_receipt') {

                $url = 'admin/sales-invoices-return/expenses-receipts/' . $invoice->id . '#show' . $expenseReceipt->id;
            }
        }

        if ($request->has('purchase_invoice_id') && $request['purchase_invoice_id'] != null) {

            if ($request['save_type'] == 'save_and_print_receipt') {

                $url = 'admin/purchase-invoices/expenses/' . $invoice->id . '#show' . $expenseReceipt->id;
            }
        }

        if ($request['save_type'] == 'save_and_print_receipt' && !$request->has('sales_invoice_return_id')
            && !$request->has('purchase_invoice_id')) {

            $url = 'admin/expenseReceipts#show' . $expenseReceipt->id;
        }

        if ($expenseReceipt && $request->has('locker_id')) {

            $locker = Locker::find($request['locker_id']);

            if (!$locker || $locker->balance < $request['cost']) {

                return redirect()->back()->with(['message' => __('sorry, this locker you can not use'), 'alert-type' => 'error']);
            }

            $locker->update([
                'balance' => ($locker->balance - $request['cost'])
            ]);

            return redirect()->to($url)
                ->with(['message' => __('words.expense-receipt-created'), 'alert-type' => 'success']);
        }

        if ($expenseReceipt && $request->has('account_id')) {

            $account = Account::find($request['account_id']);

            if (!$account || $account->balance < $request['cost']) {

                return redirect()->back()->with(['message' => __('sorry, this account you can not use'), 'alert-type' => 'error']);
            }

            $account->update([
                'balance' => ($account->balance - $request['cost'])
            ]);

//            $this->addBalanceInBankAccount($request->all());
            return redirect()->to($url)
                ->with(['message' => __('words.expense-receipt-created'), 'alert-type' => 'success']);
        }
    }

    public function edit(ExpensesReceipt $expenseReceipt, Request $request)
    {
        if (!auth()->user()->can('update_expense_receipts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($expenseReceipt->sales_invoice_return_id) {

            return $this->editFormForSalesInvoiceReturn($expenseReceipt);
        }

        if ($request->has('invoice_id') && $request->invoice_id !== null) {
            $invoice = PurchaseInvoice::find($request->invoice_id);
            $expenses = ExpensesReceipt::where('purchase_invoice_id', $invoice->id);
            if ($request->hasAny((new ExpensesReceipt())->getFillable())) {
                $expenses = $this->expenseReceiptFilter->filterInvoices($expenses, $request);
            }
            $expenses = $expenses->get();
            $expenseSum = ExpensesReceipt::where('purchase_invoice_id', $invoice->id)->sum('cost');
            $remaining = $invoice->total_after_discount - $expenseSum;

            $lockers = Locker::where('status', 1)->where('branch_id', $invoice->branch_id)->get();
            $accounts = Account::where('status', 1)->where('branch_id', $invoice->branch_id)->get();

            return view('admin.expenseReceipts.edit',
                compact('expenseReceipt', 'invoice', 'remaining', 'expenseSum', 'lockers', 'accounts'));
        }

        $lockers = Locker::where('status', 1)->get();
        $accounts = Account::where('status', 1)->get();

        return view('admin.expenseReceipts.edit', compact('expenseReceipt', 'lockers', 'accounts'));
    }

    public function update(ExpenseReceiptRequest $request, ExpensesReceipt $expenseReceipt)
    {

        if (!auth()->user()->can('update_expense_receipts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {

            $url = 'admin/expenseReceipts';

            if ($request['save_type'] == 'save_and_print_receipt' && !$request->has('sales_invoice_return_id')
                && !$request->has('purchase_invoice_id')) {

                $url = 'admin/expenseReceipts#show' . $expenseReceipt->id;
            }

            if ($expenseReceipt->sales_invoice_return_id) {

                $invoice = SalesInvoiceReturn::find($request['sales_invoice_return_id']);

                $total_amount = ($invoice->paid - $expenseReceipt->cost) + $request['cost'];

                if ($total_amount > $invoice->total) {

                    return redirect()->back()
                        ->with(['message' => __('words.cost-more-required'), 'alert-type' => 'error']);
                }

                if ($invoice->type == 'cash' && $invoice->total != $request['cost']) {
                    return redirect()->back()
                        ->with(['message' => __('words.cost-not-equal-invoice'), 'alert-type' => 'error']);
                }

                $url = 'admin/sales-invoices-return/expenses-receipts/' . $invoice->id;


                if ($request['save_type'] == 'save_and_print') {

                    $url = 'admin/sales-invoices-return/?print_type=print&invoice=' . $invoice->id;
                }

                if ($request['save_type'] == 'save_and_print_receipt') {

                    $url = 'admin/sales-invoices-return/expenses-receipts/' . $invoice->id . '#show' . $expenseReceipt->id;
                }

                $this->sendNotification('return_sales_invoice_payments', 'customer',
                    [
                        'sales_invoice_return' => $invoice,
                        'message' => 'your payments to sales invoice return updated, please check'
                    ]);

                if ($invoice->customer && $invoice->customer->email) {

                    $this->sendMail($invoice->customer->email, 'sales_return_payments_status', 'sales_return_payments_edit',
                        'App\Mail\SalesInvoiceReturnPayments');
                }
            }

            if ($request->has('purchase_invoice_id') && $request['purchase_invoice_id'] != null) {

                $invoice = PurchaseInvoice::find($request['purchase_invoice_id']);

                $total_amount = ($invoice->paid - $expenseReceipt->cost) + $request['cost'];

                if ($total_amount > $invoice->total) {

                    return redirect()->back()
                        ->with(['message' => __('words.cost-more-required'), 'alert-type' => 'error']);
                }

                if ($invoice->type == 'cash' && $invoice->total != $request['cost']) {
                    return redirect()->back()
                        ->with(['message' => __('words.cost-not-equal-invoice'), 'alert-type' => 'error']);
                }


                $url = 'admin/purchase-invoices/expenses/' . $invoice->id;

                if ($request['save_type'] == 'save_and_print') {

                    $url = 'admin/purchase-invoices/?print_type=print&invoice=' . $invoice->id;
                }

                if ($request['save_type'] == 'save_and_print_receipt') {

                    $url = 'admin/purchase-invoices/expenses/' . $invoice->id . '#show' . $expenseReceipt->id;
                }
            }

            DB::beginTransaction();

//            $this->reset($expenseReceipt);

            $data = $request->only(['time', 'date', 'expense_type_id', 'expense_item_id', 'for', 'receiver', 'payment_type',
                'bank_name', 'check_number', 'deportation', 'user_account_type', 'user_account_id', 'cost_center_id']);

            $expenseReceipt->update($data);

//            if ($expenseReceipt && $request->has('locker_id')) {
//
//                $locker = Locker::find($request['locker_id']);
//
//                if (!$locker || $locker->balance < $request['cost']) {
//
//                    return redirect()->back()->with(['message' => __('sorry, this locker you can not use'), 'alert-type' => 'error']);
//                }
//
//                $locker->update([
//                    'balance' => ($locker->balance - $request['cost'])
//                ]);
//            }
//
//            if ($expenseReceipt && $request->has('account_id')) {
//
//                $account = Account::find($request['account_id']);
//
//                if (!$account || $account->balance < $request['cost']) {
//
//                    return redirect()->back()->with(['message' => __('sorry, this account you can not use'), 'alert-type' => 'error']);
//                }
//
//                $account->update([
//                    'balance' => ($account->balance - $request['cost'])
//                ]);
//            }

            DB::commit();
        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }

        return redirect()->to($url)->with(['message' => __('words.expense-receipt-created'), 'alert-type' => 'success']);


//        if ($request->has('locker_id') && $request->locker_id !== $expenseReceipt->locker_id) {
//            $this->returnBalanceToSafe($expenseReceipt->locker_id, $expenseReceipt->cost);
//            $this->addBalanceInLocker($request->all());
//            $expenseReceipt->update($request->all());
//            return redirect()->to($url)
//                ->with(['message' => __('words.expense-receipt-updated'), 'alert-type' => 'success']);
//        }
//
//        if ($request->has('account_id') && $request->account_id !== $expenseReceipt->account_id) {
//            $this->returnBalanceToBankAccount($expenseReceipt->account_id, $expenseReceipt->cost);
//            $this->addBalanceInBankAccount($request->all());
//            $expenseReceipt->update($request->all());
//            return redirect()->to($url)
//                ->with(['message' => __('words.expense-receipt-updated'), 'alert-type' => 'success']);
//        }
//
//        if ($request->cost == $expenseReceipt->cost  && $request->has('locker_id')) {
//            $expenseReceipt->update($request->all());
//            return redirect()->to($url)
//                ->with(['message' => __('words.expense-receipt-updated'), 'alert-type' => 'success']);
//        }
//
//        if ($request->cost == $expenseReceipt->cost  && $request->has('account_id')) {
//            $expenseReceipt->update($request->all());
//            return redirect()->to($url)
//                ->with(['message' => __('words.expense-receipt-updated'), 'alert-type' => 'success']);
//        }
//        $data = $request->all();
//        if ($request->cost > $expenseReceipt->cost  && $request->has('locker_id')) {
//            $data['cost'] = $request->cost - $expenseReceipt->cost;
//            $this->addBalanceInLocker($data);
//            $expenseReceipt->update($request->all());
//            return redirect()->to($url)
//                ->with(['message' => __('words.expense-receipt-updated'), 'alert-type' => 'success']);
//        }
//        if ($expenseReceipt->cost > $request->cost  && $request->has('locker_id')) {
//            $data['cost'] = $expenseReceipt->cost - $request->cost;
//            $this->reduceBalanceInLocker($data);
//            $expenseReceipt->update($request->all());
//            return redirect()->to($url)
//                ->with(['message' => __('words.expense-receipt-updated'), 'alert-type' => 'success']);
//        }
//
//        if ($request->cost > $expenseReceipt->cost  && $request->has('account_id')) {
//            $data['cost'] = $request->cost - $expenseReceipt->cost;
//            $this->addBalanceInBankAccount($data);
//            $expenseReceipt->update($request->all());
//            return redirect()->to($url)
//                ->with(['message' => __('words.expense-receipt-updated'), 'alert-type' => 'success']);
//        }
//        if ($expenseReceipt->cost > $request->cost  && $request->has('account_id')) {
//            $data['cost'] = $expenseReceipt->cost - $request->cost;
//            $this->reduceBalanceInBankAccount($data);
//            $expenseReceipt->update($request->all());
//            return redirect()->to($url)
//                ->with(['message' => __('words.expense-receipt-updated'), 'alert-type' => 'success']);
//        }
    }

    public function destroy(ExpensesReceipt $expenseReceipt)
    {

        if (!auth()->user()->can('delete_expense_receipts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($expenseReceipt->salesInvoiceReturn) {

            $this->sendNotification('return_sales_invoice_payments', 'customer',
                [
                    'sales_invoice_return' => $expenseReceipt->salesInvoiceReturn,
                    'message' => 'your payments to sales invoice return deleted, please check'
                ]);

            if ($expenseReceipt->salesInvoiceReturn->customer && $expenseReceipt->salesInvoiceReturn->customer->email) {

                $this->sendMail($expenseReceipt->salesInvoiceReturn->customer->email, 'sales_return_payments_status', 'sales_return_payments_delete',
                    'App\Mail\SalesInvoiceReturnPayments');
            }
        }

        if ($expenseReceipt->locker_id !== null) {
            $url = $expenseReceipt->purchase_invoice_id !== null ?
                'admin/purchase-invoices/expenses/' . $expenseReceipt->purchase_invoice_id : "admin/expenseReceipts";
            $this->removeBalanceFromLocker($expenseReceipt);
            $expenseReceipt->delete();
            return redirect()->to($url)
                ->with(['message' => __('words.expense-receipt-deleted'), 'alert-type' => 'success']);
        }
        if ($expenseReceipt->account_id !== null) {
            $url = $expenseReceipt->purchase_invoice_id !== null ?
                'admin/purchase-invoices/expenses/' . $expenseReceipt->purchase_invoice_id : "admin/expenseReceipts";
            $this->removeBalanceFromAccount($expenseReceipt);
            $expenseReceipt->delete();
            return redirect()->to($url)
                ->with(['message' => __('words.expense-receipt-deleted'), 'alert-type' => 'success']);
        }
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_expense_receipts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            $this->removeBulkBalance(ExpensesReceipt::whereIn('id', $request->ids)->get());
            // remove restriction first when soft delete removed from expense receipts will havnt issue
            ExpensesReceipt::deleteSelectedFromRestriction($request->ids);
            ExpensesReceipt::whereIn('id', $request->ids)->delete();
            return redirect()->to('admin/expenseReceipts')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/expenseReceipts')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
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
        }
    }

    public function removeBalanceFromLocker(ExpensesReceipt $receipt)
    {
        $locker = Locker::find($receipt->locker_id);
        if ($locker) {
            $locker->update([
                'balance' => ($locker->balance + $receipt->cost)
            ]);
        }
    }

    public function removeBalanceFromAccount(ExpensesReceipt $receipt)
    {
        $account = Account::find($receipt->account_id);
        if ($account) {
            $account->update([
                'balance' => ($account->balance + $receipt->cost)
            ]);
        }
    }

    public function getExpenseItemsByTypeId(Request $request)
    {
        $items = ExpensesItem::where('expense_id', $request->expense_type_id)->get();

        $htmlItems = '<option value="">' . __('words.select-one') . '</option>';
        foreach ($items as $item) {
            $htmlItems .= '<option value="' . $item->id . '">' . $item->item . '</option>';
        }
        return response()->json([
            'items' => $htmlItems,
        ]);
    }

    public function addBalanceInLocker(array $data)
    {
        $locker = Locker::find($data['locker_id']);
        if ($locker) {
            $locker->update([
                'balance' => ($locker->balance - $data['cost'])
            ]);
        }
    }

    public function addBalanceInBankAccount(array $data)
    {
        $account = Account::find($data['account_id']);
        if ($account) {
            $account->update([
                'balance' => ($account->balance - $data['cost'])
            ]);
        }
    }

    public function reduceBalanceInLocker(array $data)
    {
        $locker = Locker::find($data['locker_id']);
        if ($locker) {
            $locker->update([
                'balance' => ($locker->balance + $data['cost'])
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

    public function checkIFBalanceEnough(Request $request)
    {
        //check in create
        if ($request->locker_id !== 'undefined' && !$request->has('ex_id')) {
            $locker = Locker::find($request->locker_id);
            return response()->json([
                'locker' => $locker->balance >= $request->cost,
                'account' => false,
            ]);
        }
        //check in create
        if ($request->account_id !== 'undefined' && !$request->has('ex_id')) {
            $account = Account::find($request->account_id);
            return response()->json([
                'account' => $account->balance >= $request->cost,
                'locker' => false,
            ]);
        }

        //check in update
        if ($request->locker_id !== 'undefined' && $request->has('ex_id')) {
            $expenseReceipt = ExpensesReceipt::find($request->ex_id);
            $realCost = $request->cost - $expenseReceipt->cost;
            $locker = Locker::find($request->locker_id);
            return response()->json([
                'locker' => $locker->balance >= $realCost,
                'account' => false,
            ]);
        }

        //check in update
        if ($request->account_id !== 'undefined' && $request->has('ex_id')) {
            $expenseReceipt = ExpensesReceipt::find($request->ex_id);
            $realCost = $request->cost - $expenseReceipt->cost;
            $account = Account::find($request->account_id);
            return response()->json([
                'account' => $account->balance >= $realCost,
                'locker' => false,
            ]);
        }
    }

    public function show(int $id)
    {
        $expensesReceipt = ExpensesReceipt::find($id);
        return view('admin.expenseReceipts.parts.show', compact('expensesReceipt'));

    }

    public function getExpenseNumbersByBranch(Request $request)
    {
        $numbers = ExpensesReceipt::where('branch_id', $request->branch_id)->get();

        $htmlNumbers = '';
        foreach ($numbers as $number) {
            $htmlNumbers .= '<option value="' . $number->id . '">' . $number->id . '</option>';
        }
        return response()->json([
            'numbers' => $htmlNumbers,
        ]);
    }

    public function getExpenseReceiversByBranch(Request $request)
    {
        $receivers = ExpensesReceipt::where('branch_id', $request->branch_id)->get();

        $htmlReceivers = '<option value="">' . __('words.select-one') . '</option>';
        foreach ($receivers as $receiver) {
            $htmlReceivers .= '<option value="' . $receiver->receiver . '">' . $receiver->receiver . '</option>';
        }
        return response()->json([
            'receivers' => $htmlReceivers,
        ]);
    }
}
