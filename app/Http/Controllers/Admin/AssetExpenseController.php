<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\AssetExpenseRequest;
use App\Http\Requests\Admin\Asset\AssetExpenseRequestUpdate;
use App\Http\Requests\Admin\Asset\UpdateExpenseItemRequest;
use App\Models\Asset;
use App\Models\AssetExpense;
use App\Models\AssetExpenseItem;
use App\Models\AssetGroup;
use App\Models\AssetsItemExpense;
use App\Models\AssetsTypeExpense;
use App\Models\Branch;
use App\Traits\LoggerError;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class AssetExpenseController
 * @package App\Http\Controllers\Admin
 * @author Eng-Hamdi Antar <hamdiantar27@gmail.com>
 */
class AssetExpenseController extends Controller
{
    use LoggerError;

    public function index(): View
    {
        $assetsExpenses = AssetExpense::query();
        return view('admin.assets_expenses.index', ['assetsExpenses' => $assetsExpenses->orderBy('id', 'desc')->get()]);
    }

    public function create(Request $request): View
    {
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;
        $assetsGroups = AssetGroup::where('branch_id', $branch_id)->get();
        $assets = Asset::where('branch_id', $branch_id)->get();
        $branches = Branch::all();
        $lastNumber = AssetExpense::orderBy('id', 'desc')->first();
        $number = $lastNumber ? $lastNumber->number + 1 : 1;
        return view('admin.assets_expenses.create',
            compact('assets', 'assetsGroups', 'branches', 'number'));
    }

    public function store(AssetExpenseRequest $request): RedirectResponse
    {
        try {
            $data = $request->all();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            $asset = AssetExpense::create($data);
            if ($asset) {
                foreach ($request->items as $item) {
                    AssetExpenseItem::create([
                        'price' => $item['price'],
                        'asset_id' => $item['asset_id'],
                        'asset_expense_id' => $asset->id,
                        'asset_expense_item_id' => $item['asset_expense_item_id'],
                    ]);
                }
            }
            return redirect()->to('admin/assets_expenses')
                ->with(['message' => __('words.expense-item-created'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->with(['message' => __('words.something-went-wrong'), 'alert-type' => 'error']);
        }
    }

    public function edit(Request $request, int $id)
    {
        $assetExpense = AssetExpense::findOrFail($id);
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : $assetExpense->branch_id;
        $assetsGroups = AssetGroup::where('branch_id', $branch_id)->get();
        $assets = Asset::where('branch_id', $branch_id)->get();
        $branches = Branch::all();
        $lastNumber = AssetExpense::orderBy('id', 'desc')->first();
        $number = $lastNumber ? $lastNumber->number + 1 : 1;
        $assetExpensesTypes = AssetsTypeExpense::where('branch_id', $branch_id)->get();
        $assetExpensesItems = AssetsItemExpense::where('branch_id', $branch_id)->get();
        return view('admin.assets_expenses.edit',
            compact('assets', 'assetsGroups', 'branches', 'number', 'assetExpense', 'assetExpensesItems', 'assetExpensesTypes'));
    }

    public function update(AssetExpenseRequestUpdate $request, int $id): RedirectResponse
    {
        try {
            $assetExpense = AssetExpense::findOrFail($id);
            $data = $request->all();
            $assetExpenseUpdated = $assetExpense->update($data);
            $assetExpense->assetExpensesItems()->delete();
            if ($assetExpenseUpdated) {
                foreach ($request->items as $item) {
                    AssetExpenseItem::create([
                        'price' => $item['price'],
                        'asset_id' => $item['asset_id'],
                        'asset_expense_id' => $assetExpense->id,
                        'asset_expense_item_id' => $item['asset_expense_item_id'],
                    ]);
                }
            }
            return redirect()->to('admin/assets_expenses')
                ->with(['message' => __('words.expense-item-created'), 'alert-type' => 'success']);
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

    public function getAssetsByAssetGroup(Request $request): JsonResponse
    {
        $assets = Asset::where('asset_group_id', $request->asset_group_id)->get();
        $htmlAssets = '<option value="">'.__('Select Assets').'</option>';
        foreach ($assets as $asset) {
            $htmlAssets .= '<option value="' . $asset->id . '">' . $asset->name . '</option>';
        }
        return response()->json([
            'assets' => $htmlAssets,
        ]);
    }

    public function getItemsByAssetId(Request $request): JsonResponse
    {
        if (is_null($request->asset_id)) {
            return response()->json(__('please select valid Asset'), 400);
        }
        if (is_null($request->branch_id)) {
            return response()->json(__('please select valid branch'), 400);
        }
        $index = rand(1,900000);
        $asset = Asset::with('group')->find($request->asset_id);
        $assetGroup = $asset->group;
        $assetExpensesTypes = AssetsTypeExpense::where('branch_id', $request->branch_id)->get();
        $assetExpensesItems = AssetsItemExpense::where('branch_id', $request->branch_id)->get();
        $view = view('admin.assets_expenses.row',
            compact('asset', 'assetGroup', 'assetExpensesTypes', 'assetExpensesItems', 'index')
        )->render();
        return response()->json([
            'items' => $view
        ]);
    }
}
