<?php

namespace App\Http\Controllers\Admin;

use App\Filters\RevenueTypeFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RevenueType\RevenueTypeRequest;
use App\Http\Requests\Admin\RevenueType\UpdateRevenueTypeRequest;
use App\Models\RevenueType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RevenueTypesController extends Controller
{
    /**
     * @var RevenueTypeFilter
     */
    protected $revenueTypeFilter;

    public function __construct(RevenueTypeFilter $revenueTypeFilter)
    {
        $this->revenueTypeFilter = $revenueTypeFilter;
//        $this->middleware('permission:view_revenue_type');
//        $this->middleware('permission:create_revenue_type',['only'=>['create','store']]);
//        $this->middleware('permission:update_revenue_type',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_revenue_type',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_revenue_type')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $revenueTypes = RevenueType::query();
        if ($request->hasAny((new RevenueType())->getFillable())) {
            $revenueTypes = $this->revenueTypeFilter->filter($request);
        }
        return view('admin.revenues_types.index', ['revenueTypes' => $revenueTypes->orderBy('id' ,'desc')->get()]);
    }

    public function create()
    {
        if (!auth()->user()->can('create_revenue_type')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.revenues_types.create');
    }

    public function store(RevenueTypeRequest $request)
    {
        if (!auth()->user()->can('create_revenue_type')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        RevenueType::create($data);
        return redirect()->to('admin/revenues_types')
            ->with(['message' => __('words.revenue-type-created'), 'alert-type' => 'success']);
    }

    public function edit(RevenueType $revenueType)
    {
        if (!auth()->user()->can('update_revenue_type')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.revenues_types.edit', compact('revenueType'));
    }

    public function update(UpdateRevenueTypeRequest $request, RevenueType $revenueType)
    {
        if (!auth()->user()->can('update_revenue_type')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        $revenueType->update($data);
        return redirect()->to('admin/revenues_types')
            ->with(['message' => __('words.revenue-type-updated'), 'alert-type' => 'success']);
    }

    public function destroy(RevenueType $revenueType)
    {
        if (!auth()->user()->can('delete_revenue_type')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $revenueType->delete();
        return redirect()->to('admin/revenues_types')
            ->with(['message' => __('words.revenue-type-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_revenue_type')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            RevenueType::whereIn('id', $request->ids)->delete();
            return redirect()->to('admin/revenues_types')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/revenues_types')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }
}
