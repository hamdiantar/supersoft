<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Models\Branch;
use App\Models\Locker;
use Illuminate\Http\Request;
use App\Models\LockerTransfer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\LockersTransfers;
use App\Http\Requests\Admin\LockerTransfer\createLockerTransferRequest;

class LockerTransferController extends Controller
{

    public function __construct()
    {
//        $this->middleware('permission:view_locker_transfers');
//        $this->middleware('permission:create_locker_transfers',['only'=>['create','store']]);
//        $this->middleware('permission:update_locker_transfers',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_locker_transfers',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {

        if (!auth()->user()->can('view_locker_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
            $lang = app()->getLocale() == 'ar' ? 'ar' : 'en';
            $sort_fields = [
                'id' => 'id',
                'locker-from' => 'locker_from',
                'locker-to' => 'locker_to',
                'the-cost' => 'amount',
                'created-by' => 'created_by',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at',
            ];
            $lockersTransfer = LockerTransfer::orderBy($sort_fields[$sort_by] ,$sort_method);
        } else {
            $lockersTransfer = LockerTransfer::orderBy('id','DESC');
        }

        if($request->has('locker_from_id') && $request['locker_from_id'] != '')
            $lockersTransfer->where('locker_from_id',$request['locker_from_id']);

        if($request->has('locker_to_id') && $request['locker_to_id'] != '')
            $lockersTransfer->where('locker_to_id',$request['locker_to_id']);

        if($request->has('created_by') && $request['created_by'] != '')
            $lockersTransfer->where('created_by',$request['created_by']);

        if($request->has('date_from') && $request['date_from'] != '')
            $lockersTransfer->where('date','>=',$request['date_from']);

        if($request->has('date_to') && $request['date_to'] != '')
            $lockersTransfer->where('date','<=',$request['date_to']);

        if($request->has('branch_id') && $request['branch_id'] != '')
            $lockersTransfer->where('branch_id',$request['branch_id']);

        if ($request->has('key')) {
            $key = $request->key;
            $lockersTransfer->where(function ($q) use ($key) {
                $q->where('created_at' ,'like' ,"%$key%")
                ->orWhere('updated_at' ,'like' ,"%$key%")
                ->orWhere('amount' ,'like' ,"%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker ,['print' ,'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (
                new ExportPrinterFactory(
                    new LockersTransfers($lockersTransfer->with(['lockerFrom' ,'lockerTo' ,'createdBy']) ,$visible_columns) ,
                    $request->invoker
                )
            )();
        }

        $rows = $request->has('rows') ? $request->rows : 10;
        $lockersTransfer = $lockersTransfer->paginate($rows)->appends(request()->query());

        $branches = filterSetting() ? Branch::all()->pluck('name','id') : null;
        $lockers = filterSetting() ? Locker::where('status',1)->get()->pluck('name','id') : null;
        $users = filterSetting() ? User::orderBy('id','ASC')->branch()->get()->pluck('name','id') : null;

        return view('admin.lockers-transfer.index',
            compact('branches','lockers','lockersTransfer','users'));
    }

    public function create()
    {

        if (!auth()->user()->can('create_locker_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        $lockers = Locker::where('status',1)->get()->pluck('name','id');
        return view('admin.lockers-transfer.create',compact('branches','lockers'));
    }

    public function store(createLockerTransferRequest $request)
    {

        if (!auth()->user()->can('create_locker_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{
            $data = $request->validated();

            $data['created_by'] = auth()->id();

            if($request['locker_from_id'] == $request['locker_to_id']){
                return redirect()->back()->with(['message' => __('words.lockers-is-same')
                    ,'alert-type'=>'error']);
            }

            $locker_from = Locker::findOrFail($request['locker_from_id']);
            $locker_to = Locker::findOrFail($request['locker_to_id']);

            if($request['amount'] > $locker_from->balance){
                return redirect()->back()->with(['message' => __('words.locker-money-issue')
                    ,'alert-type'=>'error']);
            }

            $locker_from->balance -= $request['amount'];
            $locker_from->save();

            $locker_to->balance += $request['amount'];
            $locker_to->save();

            if(!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            LockerTransfer::create($data);

        }catch (\Exception $e){

            dd($e->getMessage());

            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:lockers-transfer.index'))
            ->with(['message' => __('words.transfer-created'),'alert-type'=>'success']);
    }

    public function edit(LockerTransfer $lockers_transfer)
    {

        if (!auth()->user()->can('update_locker_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        $lockers = Locker::where('status',1)->get()->pluck('name','id');

        return view('admin.lockers-transfer.edit',compact('branches','lockers','lockers_transfer'));
    }

    public function update(createLockerTransferRequest $request, LockerTransfer $lockers_transfer)
    {
        if (!auth()->user()->can('update_locker_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{
            $data = $request->validated();

            $this->resetLockersAndAccountsBalance($lockers_transfer);

            if($request['locker_from_id'] == $request['locker_to_id']){
                return redirect()->back()->with(['message' => __('words.lockers-is-same')
                    ,'alert-type'=>'error']);
            }

            $locker_from = Locker::findOrFail($request['locker_from_id']);
            $locker_to = Locker::findOrFail($request['locker_to_id']);

            if($request['amount'] > $locker_from->balance){
                return redirect()->back()->with(['message' => __('words.locker-money-issue')
                    ,'alert-type'=>'error']);
            }

            $locker_from->balance -= $request['amount'];
            $locker_from->save();

            $locker_to->balance += $request['amount'];
            $locker_to->save();

            if(!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            $lockers_transfer->update($data);

        }catch (\Exception $e){

            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:lockers-transfer.index'))
            ->with(['message' => __('words.transfer-updated'),'alert-type'=>'success']);
    }

    public function resetLockersAndAccountsBalance($lockers_transfer){

        $lockers_transfer->lockerFrom->balance += $lockers_transfer->amount;
        $lockers_transfer->lockerFrom->save();

        $lockers_transfer->lockerTo->balance -= $lockers_transfer->amount;
        $lockers_transfer->lockerTo->save();

        return true;
    }

    public function destroy(LockerTransfer $lockers_transfer)
    {
        if (!auth()->user()->can('delete_locker_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $this->resetLockersAndAccountsBalance($lockers_transfer);

        $lockers_transfer->delete();

        return redirect(route('admin:lockers-transfer.index'))
            ->with(['message' => __('words.transfer-deleted') ,'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_locker_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {

            foreach($request['ids'] as $id){

                $transfer = LockerTransfer::find($id);

                $this->resetLockersAndAccountsBalance($transfer);

                $transfer->delete();
            }

            return redirect(route('admin:lockers-transfer.index'))
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }

        return redirect(route('admin:lockers-transfer.index'))
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function dataByBranch(Request $request){

        $lockers = Locker::where('branch_id', $request['id'])->get()->pluck('name','id');
        $users = User::where('branch_id', $request['id'])->get()->pluck('name','id');

        return view('admin.lockers-transfer.ajax_search',compact('lockers','users'));
    }

    public function dataByBranchInForm(Request $request){

        $lockers = Locker::where('branch_id', $request['id'])->get()->pluck('name','id');
        return view('admin.lockers-transfer.ajax_form',compact('lockers'));
    }

    function show($id) {
        try {
            $locker_transfer = LockerTransfer::with([
                'locker_transfer_pivot' => function ($q) {
                    $q->with([
                        'locker_exchange_permission' => function ($subQ) {
                            $subQ->with([
                                'fromLocker' => function ($query) {
                                    $query->select('id' ,'name_ar' ,'name_en');
                                },
                                'toLocker' => function ($query) {
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
                        'locker_receive_permission' => function ($subQ) {
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
        $code = view('admin.money-permissions.locker-transfer' ,compact('locker_transfer'))->render();
        return response(['code' => $code]);
    }

}
