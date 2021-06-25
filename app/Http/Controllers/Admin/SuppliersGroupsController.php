<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SuppliersGroups\CreateSuppliersGroupsRequest;
use App\Http\Requests\Admin\SuppliersGroups\UpdateSupplierGroupRequest;
use App\Models\Branch;
use App\Models\SupplierGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SuppliersGroupsController extends Controller
{

    public function __construct()
    {
//        $this->middleware('permission:view_supplier_groups');
//        $this->middleware('permission:create_supplier_groups',['only'=>['create','store']]);
//        $this->middleware('permission:update_supplier_groups',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_supplier_groups',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_supplier_groups')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $groups = SupplierGroup::orderBy('id','DESC');

//        if(!authIsSuperAdmin())
//            $groups->where('branch_id', auth()->user()->branch_id);

        if($request->has('name') && $request['name'] != '')
            $groups->where('id',$request['name']);

        if($request->has('branch_id') && $request['branch_id'] != '')
            $groups->where('branch_id',$request['branch_id']);

        if($request->has('active') && $request['active'] != '')
            $groups->where('status',1);

        if($request->has('inactive') && $request['inactive'] != '')
            $groups->where('status',0);

        $groups = $groups->get();

        $branches = filterSetting() ?  Branch::all()->pluck('name','id') : null;
        $suppliers_groups = filterSetting() ?  SupplierGroup::all()->pluck('name','id') : null;

        return view('admin.suppliers-groups.index',compact('groups','suppliers_groups','branches'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_supplier_groups')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        return view('admin.suppliers-groups.create',compact('branches'));
    }

    public function store(CreateSuppliersGroupsRequest $request)
    {
        if (!auth()->user()->can('create_supplier_groups')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{

            $data = $request->validated();

            $data['status'] = 0;

            if($request->has('status'))
                $data['status'] = 1;

            if(!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            SupplierGroup::create($data);

        }catch (\Exception $e){

            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:suppliers-groups.index'))
            ->with(['message' => __('words.group-created'),'alert-type'=>'success']);
    }

    public function show(SupplierGroup $suppliers_group)
    {
        if (!auth()->user()->can('show_supplier_groups')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.suppliers-groups.show',compact('suppliers_group'));
    }

    public function edit(SupplierGroup $suppliers_group)
    {
        if (!auth()->user()->can('update_supplier_groups')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        return view('admin.suppliers-groups.edit',compact('branches','suppliers_group'));
    }

    public function update(UpdateSupplierGroupRequest $request, SupplierGroup $suppliers_group)
    {
        if (!auth()->user()->can('update_supplier_groups')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{

            $data = $request->validated();

            $data['status'] = 0;

            if($request->has('status'))
                $data['status'] = 1;

            if(!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            $suppliers_group->update($data);

        }catch (\Exception $e){

            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:suppliers-groups.index'))
            ->with(['message' => __('words.group-updated'),'alert-type'=>'success']);
    }

    public function destroy(SupplierGroup $suppliers_group)
    {
        if (!auth()->user()->can('delete_supplier_groups')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $suppliers_group->delete();
        return redirect(route('admin:suppliers-groups.index'))
            ->with(['message' => __('words.group-deleted'),'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_supplier_groups')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            SupplierGroup::whereIn('id', $request->ids)->delete();
            return redirect(route('admin:suppliers-groups.index'))
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect(route('admin:suppliers-groups.index'))
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }
}
