<?php

namespace App\Http\Controllers\Admin;

use App\Filters\ExpenseItemFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExpenseItem\ExpenseItemRequest;
use App\Http\Requests\Admin\ExpenseItem\UpdateExpenseItemRequest;
use App\Models\Account;
use App\Models\ExpensesItem;
use App\Models\ExpensesReceipt;
use App\Models\ExpensesType;
use App\Models\Locker;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpensesItemsController extends Controller
{
    /**
     * @var ExpenseItemFilter
     */
    protected $expenseItemFilter;

    public function __construct(ExpenseItemFilter $expenseItemFilter)
    {
        $this->expenseItemFilter = $expenseItemFilter;
//        $this->middleware('permission:view_expense_item');
//        $this->middleware('permission:create_expense_item',['only'=>['create','store']]);
//        $this->middleware('permission:update_expense_item',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_expense_item',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_expense_item')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $expenseItems = ExpensesItem::query();
        if ($request->hasAny((new ExpensesItem())->getFillable())) {
            $expenseItems = $this->expenseItemFilter->filter($request);
        }
        return view('admin.expenses_items.index', ['expenseItems' => $expenseItems->orderBy('id' ,'desc')->get()]);
    }

    public function create()
    {
        if (!auth()->user()->can('create_expense_item')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.expenses_items.create');
    }

    public function store(ExpenseItemRequest $request)
    {
        if (!auth()->user()->can('create_expense_item')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        ExpensesItem::create($data);
        return redirect()->to('admin/expenses_items')
            ->with(['message' => __('words.expense-item-created'), 'alert-type' => 'success']);
    }

    public function edit(ExpensesItem $expensesItem)
    {
        if (!auth()->user()->can('update_expense_item')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.expenses_items.edit', compact('expensesItem'));
    }

    public function update(UpdateExpenseItemRequest $request, ExpensesItem $expensesItem)
    {
        if (!auth()->user()->can('update_expense_item')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        $expensesItem->update($data);
        return redirect()->to('admin/expenses_items')
            ->with(['message' => __('words.expense-item-updated'), 'alert-type' => 'success']);
    }

    public function destroy(ExpensesItem $expensesItem)
    {
        if (!auth()->user()->can('delete_expense_item')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $expensesItem->delete();
        return redirect()->to('admin/expenses_items')
            ->with(['message' => __('words.expense-item-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_expense_item')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            ExpensesItem::whereIn('id', $request->ids)->delete();
            return redirect()->to('admin/expenses_items')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/expenses_items')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getExpensesTypesByBranchID(Request $request)
    {
        $types = ExpensesType::where('branch_id', $request->branch_id)->where('status', 1)->get();
        $lockers = Locker::where('branch_id', $request->branch_id)->where('status', 1)->get();
        $banks = Account::where('branch_id', $request->branch_id)->where('status', 1)->get();
        $banksNames = ExpensesReceipt::whereNotNull('bank_name')->where('branch_id', $request->branch_id)->get();
        $checkNumbers = ExpensesReceipt::whereNotNull('check_number')->where('branch_id', $request->branch_id)->get();

        $htmlTypes = '<option value="">'.__('words.select-one').'</option>';
        $htmlLockers = '<option value="">'.__('words.select-one').'</option>';
        $htmlBanks = '<option value="">'.__('words.select-one').'</option>';
        $banksNamesHtml = '<option value="">'.__('words.select-one').'</option>';
        $checkNumbersHtml = '<option value="">'.__('words.select-one').'</option>';
        foreach ($lockers as $locker) {
            $htmlLockers .= '<option value="' . $locker->id . '">' . $locker->name . '</option>';
        }

        foreach ($banks as $bank) {
            $htmlBanks .= '<option value="' . $bank->id . '">' . $bank->name . '</option>';
        }

        foreach ($types as $type) {
            $htmlTypes .= '<option value="' . $type->id . '">' . $type->type . '</option>';
        }

        foreach ($banksNames as $banksName) {
            $banksNamesHtml .= '<option value="' . $banksName->bank_name . '">' .$banksName->bank_name  . '</option>';
        }

        foreach ($checkNumbers as $checkNumber) {
            $checkNumbersHtml .= '<option value="' . $checkNumber->check_number . '">' .$checkNumber->check_number  . '</option>';
        }
        return response()->json([
            'types' => $htmlTypes,
            'lockers' => $htmlLockers,
            'banks' => $htmlBanks,
            'bankNames' => $banksNamesHtml,
            'checkNumbers' => $checkNumbersHtml,
        ]);
    }

}
