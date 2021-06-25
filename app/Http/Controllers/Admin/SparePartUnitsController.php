<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SparePartUnit\SparePartUnitRequest;
use App\Http\Requests\Admin\SparePartUnit\UpdateSparePartUnitRequest;
use App\Models\SparePartUnit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SparePartUnitsController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view_spareParts');
//        $this->middleware('permission:create_spareParts',['only'=>['create','store']]);
//        $this->middleware('permission:update_spareParts',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_spareParts',['only'=>['destroy','deleteSelected']]);
    }

    public function index()
    {
        if (!auth()->user()->can('view_sparePartsUnit')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $units = SparePartUnit::orderBy('id' ,'desc')->get();
        return view('admin.spare-part-units.index', compact('units'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_sparePartsUnit')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.spare-part-units.create');
    }

    public function store(SparePartUnitRequest $request)
    {
        if (!auth()->user()->can('create_sparePartsUnit')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        SparePartUnit::create($request->all());
        return redirect()->to('admin/spare-part-units')
            ->with(['message' => __('words.spare-part-unit-created'), 'alert-type' => 'success']);
    }

    public function edit(SparePartUnit $sparePartUnit)
    {
        if (!auth()->user()->can('update_sparePartsUnit')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.spare-part-units.edit', compact('sparePartUnit'));
    }

    public function update(UpdateSparePartUnitRequest $request, SparePartUnit $sparePartUnit)
    {
        if (!auth()->user()->can('update_sparePartsUnit')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $sparePartUnit->update($request->all());
        return redirect()->to('admin/spare-part-units')
            ->with(['message' => __('words.spare-part-unit-updated'), 'alert-type' => 'success']);
    }

    public function destroy(SparePartUnit $sparePartUnit)
    {
        if (!auth()->user()->can('delete_sparePartsUnit')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($sparePartUnit->parts()->exists()) {
            return redirect()->to('admin/spare-part-units')
                ->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
        }

        $sparePartUnit->forceDelete();
        return redirect()->to('admin/spare-part-units')
            ->with(['message' => __('words.spare-part-unit-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_sparePartsUnit')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            $sparePartUnits = SparePartUnit::whereIn('id', $request->ids)->get();
            foreach ($sparePartUnits as $sparePartUnit) {
                if ($sparePartUnit->parts()->exists()) {
                    return redirect()->to('admin/spare-part-units')
                        ->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
                }
            }
            foreach ($sparePartUnits as $sparePartUnit) {
                $sparePartUnit->forceDelete();
            }
            return redirect()->to('admin/spare-part-units')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/spare-part-units')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }
}
