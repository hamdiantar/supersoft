<?php


namespace App\Http\Controllers\Admin;

use App\Filters\ExpenseTypeFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\ExpenseTypeRequest;
use App\Http\Requests\Admin\Asset\UpdateExpenseTypeRequest;
use App\Models\AssetsTypeExpense;
use App\Models\ExpensesType;
use App\Traits\LoggerError;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class AssetsTypeExpenseController
 * @package App\Http\Controllers\Admin
 * @author Eng-Hamdi Antar <hamdiantar27@gmail.com>
 */
class AssetsTypeExpenseController extends Controller
{
    use LoggerError;

    public function index(): View
    {
        $expenseTypes = AssetsTypeExpense::query();
        return view('admin.assets_expenses_types.index', ['expenseTypes' => $expenseTypes->orderBy('id', 'desc')->get()]);
    }

    public function create(): View
    {
        return view('admin.assets_expenses_types.create');
    }

    public function store(ExpenseTypeRequest $request): RedirectResponse
    {
        try {
            $data = $request->all();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            AssetsTypeExpense::create($data);
            return redirect()->to('admin/assets_expenses_types')
                ->with(['message' => __('words.expense-type-created'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->with(['message' => __('words.something-went-wrong'), 'alert-type' => 'error']);
        }
    }

    public function edit(int $id)
    {
        $assetsTypeExpense = AssetsTypeExpense::findOrFail($id);
        return view('admin.assets_expenses_types.edit', compact('assetsTypeExpense'));
    }

    public function update(UpdateExpenseTypeRequest $request, int $id): RedirectResponse
    {
        $assetsTypeExpense = AssetsTypeExpense::findOrFail($id);
        try {
            $data = $request->all();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            $assetsTypeExpense->update($data);
            return redirect()->to('admin/assets_expenses_types')
                ->with(['message' => __('words.expense-type-updated'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->with(['message' => __('words.something-went-wrong'), 'alert-type' => 'error']);
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $assetsTypeExpense = AssetsTypeExpense::findOrFail($id);
        $assetsTypeExpense->delete();
        return redirect()->to('admin/assets_expenses_types')
            ->with(['message' => __('words.expense-type-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request): RedirectResponse
    {
        if (isset($request->ids)) {
            AssetsTypeExpense::whereIn('id', $request->ids)->delete();
            return redirect()->to('admin/assets_expenses_types')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/assets_expenses_types')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getExpensesTypesByBranch(Request $request): JsonResponse
    {
        $expensesTypes = AssetsTypeExpense::where('branch_id', $request->branch_id)->get();
        $htmlTypes = '<option value="">'.__('words.select-one').'</option>';
        foreach ($expensesTypes as $type) {
            $htmlTypes .= '<option value="' . $type->id . '">' . $type->name . '</option>';
        }
        return response()->json([
            'types' => $htmlTypes,
        ]);
    }
}
