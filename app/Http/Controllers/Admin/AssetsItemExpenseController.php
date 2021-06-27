<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\View\View;
use App\Traits\LoggerError;
use Illuminate\Http\Request;
use App\Models\AssetsItemExpense;
use App\Models\AssetsTypeExpense;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\Asset\ExpenseItemRequest;
use App\Http\Requests\Admin\Asset\UpdateExpenseItemRequest;

/**
 * Class AssetsItemExpenseController
 * @package App\Http\Controllers\Admin
 * @author Eng-Hamdi Antar <hamdiantar27@gmail.com>
 */
class AssetsItemExpenseController extends Controller
{
    use LoggerError;

    public function index(): View
    {
        $expenseItems = AssetsItemExpense::query();
        return view('admin.assets_expenses_items.index', ['expenseItems' => $expenseItems->orderBy('id', 'desc')->get()]);
    }

    public function create(): View
    {
        $expensesTypes = AssetsTypeExpense::all();
        return view('admin.assets_expenses_items.create', compact('expensesTypes'));
    }

    public function store(ExpenseItemRequest $request): RedirectResponse
    {
        try {
            $data = $request->all();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            AssetsItemExpense::create($data);
            return redirect()->to('admin/assets_expenses_items')
                ->with(['message' => __('words.expense-item-created'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->with(['message' => __('words.something-went-wrong'), 'alert-type' => 'error']);
        }
    }

    public function edit(int $id)
    {
        $expensesItem = AssetsItemExpense::findOrFail($id);
        $expensesTypes = AssetsTypeExpense::all();
        return view('admin.assets_expenses_items.edit', compact('expensesItem', 'expensesTypes'));
    }

    public function update(UpdateExpenseItemRequest $request, int $id): RedirectResponse
    {
        $expensesItem = AssetsItemExpense::findOrFail($id);
        try {
            $data = $request->all();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            $expensesItem->update($data);
            return redirect()->to('admin/assets_expenses_items')
                ->with(['message' => __('words.expense-item-updated'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->with(['message' => __('words.something-went-wrong'), 'alert-type' => 'error']);
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $expensesItem = AssetsItemExpense::findOrFail($id);
        $expensesItem->delete();
        return redirect()->to('admin/assets_expenses_items')
            ->with(['message' => __('words.expense-type-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request): RedirectResponse
    {
        if (isset($request->ids)) {
            AssetsItemExpense::whereIn('id', $request->ids)->delete();
            return redirect()->to('admin/assets_expenses_items')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/assets_expenses_items')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }
}
