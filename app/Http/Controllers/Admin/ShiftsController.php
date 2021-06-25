<?php

namespace App\Http\Controllers\Admin;

use App\Filters\ShiftFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Shift\ShiftRequest;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShiftsController extends Controller
{
    /**
     * @var ShiftFilter
     */
    protected $shiftFilter;

    public function __construct(ShiftFilter $shiftFilter)
    {
        $this->shiftFilter = $shiftFilter;
//        $this->middleware('permission:view_shifts');
//        $this->middleware('permission:create_shifts',['only'=>['create','store']]);
//        $this->middleware('permission:update_shifts',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_shifts',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_shifts')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $shifts = Shift::query();
        if ($request->hasAny((new Shift())->getFillable())) {
            $shifts = $this->shiftFilter->filter($request);
        }
        return view('admin.shifts.index', ['shifts' => $shifts->orderBy('id' ,'desc')->get()]);
    }

    public function create()
    {
        if (!auth()->user()->can('create_shifts')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.shifts.create');
    }

    public function store(ShiftRequest $request)
    {
        if (!auth()->user()->can('create_shifts')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        Shift::create($data);
        return redirect()->to('admin/shifts')
            ->with(['message' => __('words.shift-created'), 'alert-type' => 'success']);
    }

    public function edit(Shift $shift)
    {
        if (!auth()->user()->can('update_shifts')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.shifts.edit', compact('shift'));
    }

    public function update(ShiftRequest $request, Shift $shift)
    {
        if (!auth()->user()->can('update_shifts')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        $shift->update($data);
        return redirect()->to('admin/shifts')
            ->with(['message' => __('words.shift-updated'), 'alert-type' => 'success']);
    }

    public function destroy(Shift $shift)
    {
        if (!auth()->user()->can('delete_shifts')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $shift->delete();
        return redirect()->to('admin/shifts')
            ->with(['message' => __('words.shift-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_shifts')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            Shift::whereIn('id', $request->ids)->delete();
            return redirect()->to('admin/shifts')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/shifts')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }
}
