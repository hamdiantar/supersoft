<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Models\Branch;
use App\Models\Locker;
use App\Models\Account;
use Illuminate\Cache\Lock;
use Illuminate\Http\Request;
use App\Models\LockerTransaction;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\LockersTransactions;
use App\Http\Requests\Admin\LockersTransactions\CreateLockersTransactionsRequest;
use App\Http\Requests\Admin\LockersTransactions\UpdateLockersTransactionsRequest;

class LockersTransactionsController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view_locker_transactions');
//        $this->middleware('permission:create_locker_transactions',['only'=>['create','store']]);
//        $this->middleware('permission:update_locker_transactions',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_locker_transactions',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {

        if (!auth()->user()->can('view_locker_transactions')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
            $lang = app()->getLocale() == 'ar' ? 'ar' : 'en';
            $sort_fields = [
                'id' => 'id',
                'locker-name' => 'locker_id',
                'account-name' => 'account_id',
                'operation-type' => 'type',
                'the-cost' => 'amount',
                'created-by' => 'created_by',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at',
            ];
            $lockerTransactions = LockerTransaction::orderBy($sort_fields[$sort_by] ,$sort_method);
        } else {
            $lockerTransactions = LockerTransaction::orderBy('id','DESC');
        }

        if($request->has('locker_id') && $request['locker_id'] != '')
            $lockerTransactions->where('locker_id',$request['locker_id']);

        if($request->has('account_id') && $request['account_id'] != '')
            $lockerTransactions->where('account_id',$request['account_id']);

        if($request->has('created_by') && $request['created_by'] != '')
            $lockerTransactions->where('created_by',$request['created_by']);

        if($request->has('type') && $request['type'] != '')
            $lockerTransactions->where('type',$request['type']);

        if($request->has('date_from') && $request['date_from'] != '')
            $lockerTransactions->where('date','>=',$request['date_from']);

        if($request->has('date_to') && $request['date_to'] != '')
            $lockerTransactions->where('date','<=',$request['date_to']);

        if($request->has('branch_id') && $request['branch_id'] != '')
            $lockerTransactions->where('branch_id',$request['branch_id']);

        if($request->has('active') && $request['active'] != '')
            $lockerTransactions->where('status',1);

        if($request->has('inactive') && $request['inactive'] != '')
            $lockerTransactions->where('status',0);

        if ($request->has('key')) {
            $key = $request->key;
            $lockerTransactions->where(function ($q) use ($key) {
                $q->where('created_at' ,'like' ,"%$key%")
                ->orWhere('updated_at' ,'like' ,"%$key%")
                ->orWhere('amount' ,'like' ,"%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker ,['print' ,'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (
                new ExportPrinterFactory(
                    new LockersTransactions($lockerTransactions->with(['locker' ,'account' ,'createdBy']) ,$visible_columns) ,
                    $request->invoker
                )
            )();
        }

        $rows = $request->has('rows') ? $request->rows : 10;
        $lockerTransactions = $lockerTransactions->paginate($rows)->appends(request()->query());

        $branches = filterSetting() ? Branch::all()->pluck('name','id') : null;
        $lockers = filterSetting() ? Locker::where('status',1)->get()->pluck('name','id') : null;
        $accounts =  filterSetting() ? Account::where('status',1)->get()->pluck('name','id') : null;
        $users = filterSetting() ? User::orderBy('id','ASC')->branch()->get()->pluck('name','id') : null;

        return view('admin.lockers-transactions.index',
            compact('accounts','branches','lockers','lockerTransactions','users'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_locker_transactions')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        $lockers = Locker::where('status',1)->get()->pluck('name','id');
        $accounts = Account::where('status',1)->get()->pluck('name','id');
        return view('admin.lockers-transactions.create',compact('branches','accounts','lockers'));
    }

    public function store(CreateLockersTransactionsRequest $request)
    {
        if (!auth()->user()->can('create_locker_transactions')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{
            $data = $request->validated();

            $data['created_by'] = auth()->id();

            $locker = Locker::findOrFail($request['locker_id']);
            $account = Account::findOrFail($request['account_id']);

            if($request['type'] == 'deposit'){

                if($request['amount'] > $account->balance){
                    return redirect()->back()->with(['message' => __('words.account-money-issue')
                        ,'alert-type'=>'error']);
                }

                $locker->balance += $request['amount'];
                $locker->save();

                $account->balance -= $request['amount'];
                $account->save();
            }

            if($request['type'] == 'withdrawal'){

                if($request['amount'] > $locker->balance){
                    return redirect()->back()->with(['message' => __('words.locker-money-issue')
                        ,'alert-type'=>'error']);
                }

                $locker->balance -= $request['amount'];
                $locker->save();

                $account->balance += $request['amount'];
                $account->save();
            }

            if(!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            LockerTransaction::create($data);

        }catch (\Exception $e){

            dd($e->getMessage());

            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:lockers-transactions.index'))
            ->with(['message' => __('words.transaction-created'),'alert-type'=>'success']);
    }

    public function edit(LockerTransaction $lockers_transaction)
    {

        if (!auth()->user()->can('update_locker_transactions')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        $lockers = Locker::where('status',1)->get()->pluck('name','id');
        $accounts = Account::where('status',1)->get()->pluck('name','id');

        return view('admin.lockers-transactions.edit',compact('branches','accounts','lockers','lockers_transaction'));
    }

    public function update(UpdateLockersTransactionsRequest $request, LockerTransaction $lockers_transaction)
    {
        if (!auth()->user()->can('update_locker_transactions')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{
            $data = $request->validated();

            $this->resetLockersAndAccountsBalance($lockers_transaction);

            if($lockers_transaction->type == 'deposit'){

                if($request['amount'] > $lockers_transaction->account->balance){
                    return redirect()->back()->with(['message' => __('words.account-money-issue')
                        ,'alert-type'=>'error']);
                }

                $lockers_transaction->locker->balance += $request['amount'];
                $lockers_transaction->locker->save();

                $lockers_transaction->account->balance -= $request['amount'];
                $lockers_transaction->account->save();
            }

            if($lockers_transaction->type == 'withdrawal'){

                if($request['amount'] > $lockers_transaction->locker->balance){
                    return redirect()->back()->with(['message' => __('words.locker-money-issue')
                        ,'alert-type'=>'error']);
                }

                $lockers_transaction->locker->balance -= $request['amount'];
                $lockers_transaction->locker->save();

                $lockers_transaction->account->balance += $request['amount'];
                $lockers_transaction->account->save();
            }

            if(!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            $lockers_transaction->update($data);

        }catch (\Exception $e){

            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:lockers-transactions.index'))
            ->with(['message' => __('words.transaction-updated'),'alert-type'=>'success']);
    }

    public function resetLockersAndAccountsBalance($lockers_transaction){

        if($lockers_transaction->type == 'deposit'){

            $lockers_transaction->locker->balance -= $lockers_transaction->amount;
            $lockers_transaction->locker->save();

            $lockers_transaction->account->balance += $lockers_transaction->amount;
            $lockers_transaction->account->save();
        }

        if($lockers_transaction->type == 'withdrawal'){

            $lockers_transaction->locker->balance += $lockers_transaction->amount;
            $lockers_transaction->locker->save();

            $lockers_transaction->account->balance -= $lockers_transaction->amount;
            $lockers_transaction->account->save();
        }

        return true;
    }

    public function destroy(LockerTransaction $lockers_transaction)
    {

        if (!auth()->user()->can('delete_locker_transactions')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $this->resetLockersAndAccountsBalance($lockers_transaction);

        $lockers_transaction->delete();

        return redirect(route('admin:lockers-transactions.index'))
            ->with(['message' => __('words.transaction-deleted'),'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_locker_transactions')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {

            foreach($request['ids'] as $id){

                $transaction = LockerTransaction::find($id);

                $this->resetLockersAndAccountsBalance($transaction);

                $transaction->delete();
            }

            return redirect(route('admin:lockers-transactions.index'))
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }

        return redirect(route('admin:lockers-transactions.index'))
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getLockerBalance(Request $request){

        $locker = Locker::findOrFail($request['locker_id']);
        return $locker;
    }

    public function getAccountBalance(Request $request){

        $account = Account::findOrFail($request['account_id']);
        return $account;
    }

    public function dataByBranch(Request $request){

        $lockers = Locker::where('branch_id', $request['id'])->get()->pluck('name','id');
        $accounts = Account::where('branch_id', $request['id'])->get()->pluck('name','id');
        $users = User::where('branch_id', $request['id'])->get()->pluck('name','id');

        return view('admin.lockers-transactions.ajax_search',compact('lockers','users','accounts'));
    }

    public function dataByBranchInForm(Request $request){

        $lockers = Locker::where('branch_id', $request['id'])->get()->pluck('name','id');
        $accounts = Account::where('branch_id', $request['id'])->get()->pluck('name','id');

        return view('admin.lockers-transactions.ajax_form',compact('lockers','accounts'));
    }

    function show($id) {
        try {
            $locker_transaction = LockerTransaction::with([
                'branch',
                'locker' => function ($q) {
                    $q->select('id' ,'name_en' ,'name_ar');
                },
                'account' => function ($q) {
                    $q->select('id' ,'name_en' ,'name_ar');
                },
                'createdBy' => function ($q) {
                    $q->select('id' ,'name');
                }
            ])->findOrFail($id);
        } catch (Exception $e) {
            return response(['message' => __('words.transfer-not-found')] ,400);
        }
        $code = view('admin.money-permissions.locker-transaction' ,compact('locker_transaction'))->render();
        return response(['code' => $code]);
    }
}
