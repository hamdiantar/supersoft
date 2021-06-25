<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Area\AreaRequest;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AreasController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view_areas');
//        $this->middleware('permission:create_areas',['only'=>['create','store']]);
//        $this->middleware('permission:update_areas',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_areas',['only'=>['destroy','deleteSelected']]);
    }

    public function index()
    {
        if (!auth()->user()->can('view_areas')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $areas = Area::orderBy('id' ,'desc')->get();
        return view('admin.areas.index', compact('areas'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_areas')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.areas.create');
    }

    public function store(AreaRequest $request)
    {
        if (!auth()->user()->can('create_areas')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        Area::create($request->all());
        return redirect()->to('admin/areas')
            ->with(['message' => __('words.area-created'), 'alert-type' => 'success']);
    }

    public function edit(Area $area)
    {
        if (!auth()->user()->can('update_areas')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.areas.edit', compact('area'));
    }

    public function update(AreaRequest $request, Area $area)
    {
        if (!auth()->user()->can('update_areas')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $area->update($request->all());
        return redirect()->to('admin/areas')
            ->with(['message' => __('words.area-updated'), 'alert-type' => 'success']);
    }

    public function destroy(Area $area)
    {
        if ($area->city) {
            return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
        }
        if (!auth()->user()->can('delete_areas')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $area->forceDelete();
        return redirect()->to('admin/areas')
            ->with(['message' => __('words.area-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_areas')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            $areas = Area::whereIn('id', $request->ids)->get();
            foreach ($areas as $area) {
                if ($area->city) {
                    return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
                }
            }
            foreach ($areas as $area) {
                $area->forceDelete();
            }
            return redirect()->to('admin/areas')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/areas')
            ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }
}
