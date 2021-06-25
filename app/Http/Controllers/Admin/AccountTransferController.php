<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Branch;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\AccountTransfer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\AccountsTransfers;
use App\Http\Requests\Admin\AccountTransfer\CreateAccountTransferRequest;
use Exception;

class AccountTransferController extends Controller
{

    public function __construct()
    {
//        $this->middleware('permission:view_account_transfers');
//        $this->middleware('permission:create_account_transfers',['only'=>['create','store']]);
//        $this->middleware('permission:update_account_transfers',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_account_transfers',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_account_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
            $lang = app()->getLocale() == 'ar' ? 'ar' : 'en';
            $sort_fields = [
                'id' => 'id',
                'account-from' => 'account_from_id',
                'account-to' => 'account_to_id',
                'the-cost' => 'amount',
                'created-by' => 'created_by',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at',
            ];
            $accountsTransfer = AccountTransfer::orderBy($sort_fields[$sort_by] ,$sort_method);
        } else {
            $accountsTransfer = AccountTransfer::orderBy('id','DESC');
        }

        if($request->has('account_from_id') && $request['account_from_id'] != '')
            $accountsTransfer->where('account_from_id',$request['account_from_id']);

        if($request->has('account_to_id') && $request['account_to_id'] != '')
            $accountsTransfer->where('account_to_id',$request['account_to_id']);

        if($request->has('created_by') && $request['created_by'] != '')
            $accountsTransfer->where('created_by',$request['created_by']);

        if($request->has('date_from') && $request['date_from'] != '')
            $accountsTransfer->where('date','>=',$request['date_from']);

        if($request->has('date_to') && $request['date_to'] != '')
            $accountsTransfer->where('date','<=',$request['date_to']);

        if($request->has('branch_id') && $request['branch_id'] != '')
            $accountsTransfer->where('branch_id',$request['branch_id']);
        
        if ($request->has('key')) {
            $key = $request->key;
            $accountsTransfer->where(function ($q) use ($key) {
                $q->where('created_at' ,'like' ,"%$key%")
                ->orWhere('updated_at' ,'like' ,"%$key%")
                ->orWhere('amount' ,'like' ,"%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker ,['print' ,'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (
                new ExportPrinterFactory(
                    new AccountsTransfers($accountsTransfer->with(['accountFrom' ,'accountTo' ,'createdBy']) ,$visible_columns) ,
                    $request->invoker
                )
            )();
        }

        $rows = $request->has('rows') ? $request->rows : 10;
        $accountsTransfer = $accountsTransfer->paginate($rows)->appends(request()->query());

        $branches = filterSetting() ? Branch::all()->pluck('name','id') : null;
        $accounts = filterSetting() ? Account::where('status',1)->get()->pluck('name','id') : null;
        $users = filterSetting() ? User::orderBy('id','ASC')->branch()->get()->pluck('name','id') : null;

        return view('admin.accounts-transfer.index',
            compact('branches','accounts','accountsTransfer','users'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_account_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        $accounts = Account::where('status',1)->get()->pluck('name','id');
        return view('admin.accounts-transfer.create',compact('branches','accounts'));
    }

    public function store(CreateAccountTransferRequest $request)
    {
        if (!auth()->user()->can('create_account_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{
            $data = $request->validated();

            $data['created_by'] = auth()->id();

            if($request['account_from_id'] == $request['account_to_id']){
                return redirect()->back()->with(['message' => __('words.account-equal')
                    ,'alert-type'=>'error']);
            }

            $account_from = Account::findOrFail($request['account_from_id']);
            $account_to = Account::findOrFail($request['account_to_id']);

            if($request['amount'] > $account_from->balance){
                return redirect()->back()->with(['message' => __('words.account-not-enough-amount')
                    ,'alert-type'=>'error']);
            }

            $account_from->balance -= $request['amount'];
            $account_from->save();

            $account_to->balance += $request['amount'];
            $account_to->save();

            if(!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            AccountTransfer::create($data);

        }catch (\Exception $e){

            dd($e->getMessage());

            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:accounts-transfer.index'))
            ->with(['message' => __('words.transfer-created'),'alert-type'=>'success']);
    }

    public function edit(AccountTransfer $accounts_transfer)
    {
        if (!auth()->user()->can('update_account_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        $accounts = Account::where('status',1)->get()->pluck('name','id');

        return view('admin.accounts-transfer.edit',compact('branches','accounts','accounts_transfer'));
    }

    public function update(CreateAccountTransferRequest $request, AccountTransfer $accounts_transfer)
    {
        if (!auth()->user()->can('update_account_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{
            $data = $request->validated();

            $this->resetLockersAndAccountsBalance($accounts_transfer);

            if($request['account_from_id'] == $request['account_to_id']){
                return redirect()->back()->with(['message' => __('words.account-equal')
                    ,'alert-type'=>'error']);
            }

            $account_from = Account::findOrFail($request['account_from_id']);
            $account_to = Account::findOrFail($request['account_to_id']);

            if($request['amount'] > $account_from->balance){
                return redirect()->back()->with(['message' => __('words.account-not-enough-amount')
                    ,'alert-type'=>'error']);
            }

            $account_from->balance -= $request['amount'];
            $account_from->save();

            $account_to->balance += $request['amount'];
            $account_to->save();

            if(!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            $accounts_transfer->update($data);

        }catch (\Exception $e){

            dd($e->getMessage());
            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:accounts-transfer.index'))
            ->with(['message' => __('words.transfer-updated'),'alert-type'=>'success']);
    }

    public function resetLockersAndAccountsBalance($accounts_transfer){

        $accounts_transfer->accountFrom->balance += $accounts_transfer->amount;
        $accounts_transfer->accountFrom->save();

        $accounts_transfer->accountTo->balance -= $accounts_transfer->amount;
        $accounts_transfer->accountTo->save();

        return true;
    }

    public function destroy(AccountTransfer $accounts_transfer)
    {

        if (!auth()->user()->can('delete_account_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $this->resetLockersAndAccountsBalance($accounts_transfer);

        $accounts_transfer->delete();

        return redirect(route('admin:accounts-transfer.index'))
            ->with(['message' => __('words.transfer-deleted'),'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_account_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {

            foreach($request['ids'] as $id){

                $transfer = AccountTransfer::find($id);

                $this->resetLockersAndAccountsBalance($transfer);

                $transfer->delete();
            }

            return redirect(route('admin:accounts-transfer.index'))
                ->with(['message' => __('words.transfer-selected-deleted'), 'alert-type' => 'success']);
        }

        return redirect(route('admin:accounts-transfer.index'))
            ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }

    public function dataByBranch(Request $request){

        $accounts = Account::where('branch_id', $request['id'])->get()->pluck('name','id');
        $users = User::where('branch_id', $request['id'])->get()->pluck('name','id');

        return view('admin.accounts-transfer.ajax_search',compact('accounts','users'));
    }

    public function dataByBranchInForm(Request $request){

        $accounts = Account::where('branch_id', $request['id'])->get()->pluck('name','id');
        return view('admin.accounts-transfer.ajax_form',compact('accounts'));
    }

    function show($id) {
        try {
            $account_transfer = AccountTransfer::with([
                'bank_transfer_pivot' => function ($q) {
                    $q->with([
                        'bank_exchange_permission' => function ($subQ) {
                            $subQ->with([
                                'fromBank' => function ($query) {
                                    $query->select('id' ,'name_ar' ,'name_en');
                                },
                                'toBank' => function ($query) {
                                    $query->select('id' ,'name_ar' ,'name_en');
                                },
                                'employee' => function ($query) {
                                    $query->select('id' ,'name_ar' ,'name_en');
                                },
                                'cost_center' => function ($query) {
                                    $query->select('id' ,'name_'.(app()->getLocale() == 'ar' ? 'ar' : 'en').' as name');
                                }
                            ]);
                        },
                        'bank_receive_permission' => function ($subQ) {
                            $subQ->with([
                                'employee' => function ($query) {
                                    $query->select('id' ,'name_ar' ,'name_en');
                                },
                                'cost_center' => function ($query) {
                                    $query->select('id' ,'name_'.(app()->getLocale() == 'ar' ? 'ar' : 'en').' as name');
                                }
                            ]);
                        }
                    ]);
                },
                'branch'
            ])->findOrFail($id);
        } catch (Exception $e) {
            return response(['message' => __('words.transfer-not-found')] ,400);
        }
        $code = view('admin.money-permissions.bank-transfer' ,compact('account_transfer'))->render();
        return response(['code' => $code]);
    }
}
