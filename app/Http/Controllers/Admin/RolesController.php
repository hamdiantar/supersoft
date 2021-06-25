<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Authorization\CreateRoleRequest;
use App\Http\Requests\Admin\Authorization\UpdateRoleRequest;
use App\Models\Branch;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view_roles');
//        $this->middleware('permission:create_roles',['only'=>['create','store']]);
//        $this->middleware('permission:update_roles',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_roles',['only'=>['delete','deleteSelected']]);
    }

    public function index()
    {
        if (!auth()->user()->can('view_roles')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $roles = Role::orderBy('id','desc')->get();

        if(!authIsSuperAdmin()){

            $branch = auth()->user()->branch;

            if($branch)
                $roles =  $branch->roles()->orWhere('role_id', 1)->orderBy('id' ,'desc')->get();
        }

        return view('admin.roles.index',compact('roles'));
    }

    public function create()
    {

        if (!auth()->user()->can('create_roles')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $loginPermission = Permission::find(1);
        $modules = Module::all();
        $branches = Branch::all()->pluck('name','id');
        return view('admin.roles.create',compact('modules','loginPermission','branches'));
    }

    public function store(CreateRoleRequest $request)
    {

        if (!auth()->user()->can('create_roles')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }


        try {

            $data = $request->only('name');

            if(authIsSuperAdmin())
                $branch_id = $request['branch_id'];
            else
                $branch_id = auth()->user()->branch_id;

            $branch = Branch::find($branch_id);

            $data['name'] = $request['name'] . '_' . 'default branch';

            if($branch){

                $data['name'] = $request['name'] . '_' . $branch->name_en ;
            }

            $checkRole = Role::where('name', $data['name'])->first();

            if($checkRole){

                return redirect()->back()->with(['message' => __('Role Already Exists'),'alert-type'=>'error']);
            }

            DB::beginTransaction();

            $role = Role::create($data);

            $role->syncPermissions($request['permissions']);

            DB::table('branches_roles')->insert([
                'branch_id'=> $branch_id,
                'role_id'=> $role->id,
            ]);

            DB::commit();

        } catch (\Exception $e) {

//            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with(['message' => __('words.try-again'),'alert-type'=>'error']);
        }

        return redirect(route('admin:roles.index'))
            ->with(['message' => __('words.role-created'),'alert-type'=>'success']);
    }

    public function edit(Role $role)
    {
        if (!auth()->user()->can('update_roles')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $loginPermission = Permission::find(1);

        $modules = Module::all();

        $branches = Branch::all()->pluck('name','id');

        $branch_id = 1;

        $branch = DB::table('branches_roles')->where('role_id', $role->id)->first();

        if($branch){

            $branch_id = $branch->branch_id;
        }

        return view('admin.roles.edit',compact('role','modules','loginPermission','branches','branch_id'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        if (!auth()->user()->can('update_roles')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {

            if(authIsSuperAdmin())
                $branch_id = $request['branch_id'];
            else
                $branch_id = auth()->user()->branch_id;

            $branch = Branch::find($branch_id);

            $data['name'] = $request['name'] . '_' . 'default branch';

            if($branch){

                $data['name'] = $request['name'] . '_' . $branch->name_en ;
            }

            $checkRole = Role::where('id','!=', $role->id)->where('name', $data['name'])->first();

            if($checkRole){

                return redirect()->back()->with(['message' => __('Role Already Exists'),'alert-type'=>'error']);
            }

            DB::beginTransaction();

            $data = $request->only('name');

            $role->update($data);

            $role->syncPermissions($request['permissions']);

            DB::table('branches_roles')->where('role_id',$role->id)->update([
                'branch_id'=> $branch_id,
                'role_id'=> $role->id,
            ]);

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->back()->with(['message' => __('words.try-again'),'alert-type'=>'error']);
        }

        return redirect(route('admin:roles.index'))
            ->with(['message' => __('words.role-updated'),'alert-type'=>'success']);
    }

    public function destroy(Role $role)
    {
        if (!auth()->user()->can('delete_roles')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }
        if ($role->users()->exists()) {
            return redirect()->back()->with(['message'=>__('words.sorry you can not delete this role'),'alert-type'=>'error']);
        }
        if($role->id == 1) {
            return redirect()->back()->with(['message'=>__('words.sorry you can not delete this role'),'alert-type'=>'error']);
        }
        if($role->name == 'super-admin') {
            return redirect()->back()->with(['message'=>__('words.sorry you can not delete this role'),'alert-type'=>'error']);
        }
        $role->delete();
        return redirect()->back()->with(['message'=>__('words.role-deleted'),'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_roles')) {

            Alert::error(__('Authorization'), __('Not Allowed'));
            return redirect()->back();
        }

        if(is_array($request->ids) && in_array(1,$request->ids)){
            return redirect()->back()->with(['message'=>__('words.sorry you can not delete this role'),'alert-type'=>'error']);
        }

        if (isset($request->ids)) {
            $roles = Role::whereIn('id', $request->ids)->get();
            foreach ($roles as $role) {
                if ($role->users()->exists() || $role->name == 'super-admin') {
                    return redirect()->back()->with(['message'=>__('words.sorry you can not delete this role'),'alert-type'=>'error']);
                }
            }
            foreach ($roles as $role) {
                $role->delete();
            }
            return redirect(route('admin:roles.index'))
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect(route('admin:roles.index'))
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }
}
