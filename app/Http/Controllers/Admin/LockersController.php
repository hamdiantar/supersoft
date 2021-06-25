<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Lockers\CreateLockersRequest;
use App\Http\Requests\Admin\Lockers\UpdateLockerRequest;
use App\Models\Branch;
use App\Models\Locker;
use App\Models\User;
use Illuminate\Cache\Lock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LockersController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view_lockers');
//        $this->middleware('permission:create_lockers',['only'=>['create','store']]);
//        $this->middleware('permission:update_lockers',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_lockers',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {

        if (!auth()->user()->can('view_lockers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $lockers = Locker::orderBy('id','DESC');

//        if(!authIsSuperAdmin())
//            $lockers->where('branch_id', auth()->user()->branch_id);

        if($request->has('name') && $request['name'] != '')
            $lockers->where('id',$request['name']);

        if($request->has('branch_id') && $request['branch_id'] != '')
            $lockers->where('branch_id',$request['branch_id']);

        if($request->has('active') && $request['active'] != '')
            $lockers->where('status',1);

        if($request->has('inactive') && $request['inactive'] != '')
            $lockers->where('status',0);

        $lockers = $lockers->get();

        $branches = filterSetting() ? Branch::all()->pluck('name','id') : null;
        $lockers_search = filterSetting() ? Locker::all()->pluck('name','id') : null;

        return view('admin.lockers.index',
            compact('lockers','branches','lockers_search'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_lockers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        $users = User::orderBy('id','ASC')->branch()->get()->pluck('name','id');
        return view('admin.lockers.create',compact('branches','users'));
    }

    public function store(CreateLockersRequest $request)
    {

        if (!auth()->user()->can('create_lockers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{
            $data = $request->validated();

            $data['status'] = 0;

            if($request->has('status'))
                $data['status'] = 1;

            $data['special'] = 0;

            if($request->has('special'))
                $data['special'] = 1;

            if(!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            $locker = Locker::create($data);

            if($request->has('special')){
                $locker->users()->attach($request['users']);
            }

        }catch (\Exception $e){

            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:lockers.index'))
            ->with(['message' => __('words.locker-created'),'alert-type'=>'success']);
    }

    public function show(Locker $locker)
    {
        if (!auth()->user()->can('view_lockers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.lockers.show',compact('locker'));
    }

    public function edit(Locker $locker)
    {
        if (!auth()->user()->can('update_lockers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if($locker->special && !in_array( auth()->id(), $locker->users->pluck('id')->toArray()) && !authIsSuperAdmin() ){

            return redirect()->back()
                ->with(['message' => __('words.cant-access-page'),'alert-type'=>'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        $users = User::orderBy('id','ASC')->branch()->pluck('name','id');
        return view('admin.lockers.edit',compact('branches','locker','users'));
    }

    public function update(UpdateLockerRequest $request, Locker $locker)
    {
        if (!auth()->user()->can('update_lockers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{
            $data = $request->validated();

            $data['status'] = 0;

            if($request->has('status'))
                $data['status'] = 1;

            $data['special'] = 0;

            if($request->has('special'))
                $data['special'] = 1;

            if(!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            if($locker->revenueReceipts->count() || $locker->expensesReceipts->count()){
                $data['balance'] = $locker->balance;
            }

            $locker->update($data);

            if($request->has('special')){
                $locker->users()->sync($request['users']);
            }else{

                $locker->users()->detach();
            }

        }catch (\Exception $e){

            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:lockers.index'))
            ->with(['message' => __('words.locker-updated'),'alert-type'=>'success']);
    }

    public function destroy(Locker $locker)
    {
        if (!auth()->user()->can('delete_lockers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if($locker->special && !in_array( auth()->id(), $locker->users->pluck('id')->toArray() ) && !authIsSuperAdmin()){

            return redirect()->back()
                ->with(['message' => __('words.cant-access-page'),'alert-type'=>'error']);
        }

        if($locker->revenueReceipts || $locker->expensesReceipts){

            return redirect()->back()
                ->with(['message' => __('words.this locker has transaction'),'alert-type'=>'error']);
        }

        $locker->delete();
        return redirect(route('admin:lockers.index'))
            ->with(['message' => __('words.locker-deleted'),'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_lockers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {

            Locker::where(function ($q){

                $q->orWhereDoesntHave('revenueReceipts');

                $q->orWhereDoesntHave('expensesReceipts');

            })->whereIn('id', $request->ids)->delete();

            return redirect(route('admin:lockers.index'))
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);

        }
        return redirect(route('admin:lockers.index'))
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getUsers(Request $request){
        $this->validate($request,[
           'branch_id' => 'required|integer|exists:branches,id'
        ]);

        $users = User::where('branch_id', $request['branch_id'])->pluck('name','id');

        return view('admin.lockers.selected_users', compact('users'));
    }

    public function dataByBranch(Request $request){

        $lockers_search = Locker::where('branch_id', $request['id'])->get()->pluck('name','id');
        return view('admin.lockers.ajax_search',compact('lockers_search'));
    }
}
