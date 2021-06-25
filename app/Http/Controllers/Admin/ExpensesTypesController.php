<?php

namespace App\Http\Controllers\Admin;

use App\Filters\ExpenseTypeFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExpenseType\ExpenseTypeRequest;
use App\Http\Requests\Admin\ExpenseType\UpdateExpenseTypeRequest;
use App\Models\ExpensesType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpensesTypesController extends  Controller
{
    /**
     * @var ExpenseTypeFilter
     */
    protected $expenseTypeFilter;

    public function __construct(ExpenseTypeFilter $expenseTypeFilter)
    {
        $this->expenseTypeFilter = $expenseTypeFilter;
//        $this->middleware('permission:view_expense_type');
//        $this->middleware('permission:create_expense_type',['only'=>['create','store']]);
//        $this->middleware('permission:update_expense_type',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_expense_type',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_expense_type')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $expenseTypes = ExpensesType::query();
        if ($request->hasAny((new ExpensesType())->getFillable())) {
            $expenseTypes = $this->expenseTypeFilter->filter($request);
        }
        return view('admin.expenses_types.index', ['expenseTypes' => $expenseTypes->orderBy('id' ,'desc')->get()]);
    }

    public function create()
    {
        if (!auth()->user()->can('create_expense_type')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.expenses_types.create');
    }

    public function store(ExpenseTypeRequest $request)
    {
        if (!auth()->user()->can('create_expense_type')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        ExpensesType::create($data);
        return redirect()->to('admin/expenses_types')
            ->with(['message' => __('words.expense-type-created'), 'alert-type' => 'success']);
    }

    public function edit(ExpensesType $expensesType)
    {
        if (!auth()->user()->can('update_expense_type')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.expenses_types.edit', compact('expensesType'));
    }

    public function update(UpdateExpenseTypeRequest $request, ExpensesType $expensesType)
    {
        if (!auth()->user()->can('update_expense_type')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        $expensesType->update($data);
        return redirect()->to('admin/expenses_types')
            ->with(['message' => __('words.expense-type-updated'), 'alert-type' => 'success']);
    }

    public function destroy(ExpensesType $expensesType)
    {
        if (!auth()->user()->can('delete_expense_type')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $expensesType->delete();
        return redirect()->to('admin/expenses_types')
            ->with(['message' => __('words.expense-type-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_expense_type')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            ExpensesType::whereIn('id', $request->ids)->delete();
            return redirect()->to('admin/expenses_types')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/expenses_types')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }
}
