<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Accounts\CreateAccountsRequest;
use App\Http\Requests\Admin\Accounts\UpdateAccountsRequest;
use App\Models\Account;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountsController extends Controller
{

    public function __construct()
    {
//        $this->middleware('permission:view_accounts');
//        $this->middleware('permission:create_accounts',['only'=>['create','store']]);
//        $this->middleware('permission:update_accounts',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_accounts',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_accounts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $accounts = Account::orderBy('id','DESC');

        if($request->has('name') && $request['name'] != '')
            $accounts->where('id',$request['name']);

        if($request->has('branch_id') && $request['branch_id'] != '')
            $accounts->where('branch_id',$request['branch_id']);

        if($request->has('active') && $request['active'] != '')
            $accounts->where('status',1);

        if($request->has('inactive') && $request['inactive'] != '')
            $accounts->where('status',0);

        $accounts = $accounts->get();

        $branches = filterSetting() ? Branch::all()->pluck('name','id') : null;
        $accounts_search = filterSetting() ? Account::all()->pluck('name','id') : null;

        return view('admin.accounts.index',
            compact('accounts','branches','accounts_search'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_accounts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        $users = User::orderBy('id','ASC')->branch()->get()->pluck('name','id');
        return view('admin.accounts.create',compact('branches','users'));
    }

    public function store(CreateAccountsRequest $request)
    {

        if (!auth()->user()->can('create_accounts')) {
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

            $locker = Account::create($data);

            if($request->has('special')){
                $locker->users()->attach($request['users']);
            }

        }catch (\Exception $e){

            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:accounts.index'))
            ->with(['message' => __('words.account-create'),'alert-type'=>'success']);
    }

    public function show(Account $account)
    {
        if (!auth()->user()->can('view_accounts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.accounts.show',compact('account'));
    }

    public function edit(Account $account)
    {
        if (!auth()->user()->can('update_accounts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if($account->special && !in_array( auth()->id(), $account->users->pluck('id')->toArray()) && !authIsSuperAdmin()){

            return redirect()->back()
                ->with(['message' => __('words.cant-access-page'),'alert-type'=>'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        $users = User::orderBy('id','ASC')->branch()->pluck('name','id');

        return view('admin.accounts.edit',compact('branches','account','users'));
    }

    public function update(UpdateAccountsRequest $request, Account $account)
    {
        if (!auth()->user()->can('update_accounts')) {
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

            if($account->revenueReceipts->count() || $account->expensesReceipts->count()){
                $data['balance'] = $account->balance;
            }

            $account->update($data);

            if($request->has('special')){
                $account->users()->sync($request['users']);
            }else{

                $account->users()->detach();
            }

        }catch (\Exception $e){

            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:accounts.index'))
            ->with(['message' => __('words.account-updated'),'alert-type'=>'success']);
    }

    public function destroy(Account $account)
    {
        if (!auth()->user()->can('delete_accounts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if($account->special && !in_array( auth()->id(), $account->users->pluck('id')->toArray()) && !authIsSuperAdmin()){

            return redirect()->back()
                ->with(['message' => __('words.cant-access-page'),'alert-type'=>'error']);
        }

        if($account->revenueReceipts || $account->expensesReceipts){

            return redirect()->back()
                ->with(['message' => __('words.this account has transaction'),'alert-type'=>'error']);
        }

        $account->delete();
        return redirect(route('admin:accounts.index'))
            ->with(['message' => __('words.account-deleted'),'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_accounts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {

            Account::where(function ($q){

                $q->orWhereDoesntHave('revenueReceipts');

                $q->orWhereDoesntHave('expensesReceipts');

            })->whereIn('id', $request->ids)->delete();

            return redirect(route('admin:accounts.index'))
                ->with(['message' => __('words.accounts-deleted'), 'alert-type' => 'success']);
        }
        return redirect(route('admin:accounts.index'))
            ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }

    public function dataByBranch(Request $request){

        $accounts_search = Account::where('branch_id', $request['id'])->get()->pluck('name','id');
        return view('admin.accounts.ajax_search',compact('accounts_search'));
    }
}
