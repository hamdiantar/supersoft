<?php

namespace App\Http\Controllers\Admin;

use App\Filters\RevenueItemFilter;
use App\Filters\RevenueTypeFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RevenueItem\RevenueItemRequest;
use App\Http\Requests\Admin\RevenueItem\UpdateRevenueItemRequest;
use App\Models\Account;
use App\Models\Locker;
use App\Models\RevenueItem;
use App\Models\RevenueType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RevenueItemsController extends Controller
{
    /**
     * @var RevenueItemFilter
     */
    protected $revenueItemFilter;

    public function __construct(RevenueItemFilter $revenueItemFilter)
    {
        $this->revenueItemFilter = $revenueItemFilter;
//        $this->middleware('permission:view_revenue_item');
//        $this->middleware('permission:create_revenue_item',['only'=>['create','store']]);
//        $this->middleware('permission:update_revenue_item',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_revenue_item',['only'=>['destroy','deleteSelected']]);
    }
    public function index(Request $request)
    {
        if (!auth()->user()->can('view_revenue_item')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $revenueItems = RevenueItem::query();
        if ($request->hasAny((new RevenueItem())->getFillable())) {
            $revenueItems = $this->revenueItemFilter->filter($request);
        }
        return view('admin.revenues_Items.index', ['revenueItems' => $revenueItems->orderBy('id' ,'desc')->get()]);
    }

    public function create()
    {
        if (!auth()->user()->can('create_revenue_item')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.revenues_Items.create');
    }

    public function store(RevenueItemRequest $request)
    {
        if (!auth()->user()->can('create_revenue_item')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        RevenueItem::create($data);
        return redirect()->to('admin/revenues_Items')
            ->with(['message' => __('words.revenue-item-created'), 'alert-type' => 'success']);
    }

    public function edit(RevenueItem $revenueItem)
    {
        if (!auth()->user()->can('update_revenue_item')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.revenues_Items.edit', compact('revenueItem'));
    }

    public function update(UpdateRevenueItemRequest $request, RevenueItem $revenueItem)
    {
        if (!auth()->user()->can('update_revenue_item')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        $revenueItem->update($data);
        return redirect()->to('admin/revenues_Items')
            ->with(['message' => __('words.revenue-item-updated'), 'alert-type' => 'success']);
    }

    public function destroy(RevenueItem $revenueItem)
    {
        if (!auth()->user()->can('delete_revenue_item')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $revenueItem->delete();
        return redirect()->to('admin/revenues_Items')
            ->with(['message' => __('words.revenue-item-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_revenue_item')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            RevenueItem::whereIn('id', $request->ids)->delete();
            return redirect()->to('admin/revenues_Items')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/revenues_Items')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getRevenuesTypesByBranchID(Request $request)
    {
        $types = RevenueType::where('branch_id', $request->branch_id)->get();
        $lockers = Locker::where('branch_id', $request->branch_id)->where('status', 1)->get();
        $banks = Account::where('branch_id', $request->branch_id)->where('status', 1)->get();

        $htmlTypes = '<option value="">' . __('Select Revenue Type') . '</option>';
        $htmlLockers = '<option value="">'.__('Select Locker').'</option>';
        $htmlBanks = '<option value="">'.__('Select Account').'</option>';

        foreach ($types as $type) {
            $htmlTypes .= '<option value="' . $type->id . '">' . $type->type . '</option>';
        }

        foreach ($lockers as $locker) {
            $htmlLockers .= '<option value="' . $locker->id . '">' . $locker->name . '</option>';
        }

        foreach ($banks as $bank) {
            $htmlBanks .= '<option value="' . $bank->id . '">' . $bank->name . '</option>';
        }

        return response()->json(
            [
                'types' => $htmlTypes,
                'lockers' => $htmlLockers,
                'banks' => $htmlBanks,
            ]
        );
    }
}
