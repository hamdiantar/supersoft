<?php
namespace App\Http\Controllers\Admin\MoneyPermissions;

use App\AccountingModule\CoreLogic\AccountRelationLogic;
use Exception;
use App\Models\Locker;
use App\Models\Account;
use App\Models\EmployeeData;
use App\Models\LockerTransfer;
use App\Models\AccountTransfer;
use Illuminate\Database\Eloquent\Model;
use App\AccountingModule\Models\AccountRelation;
use App\Models\LockerTransaction;
use App\ModelsMoneyPermissions\BankTransferPivot;
use App\ModelsMoneyPermissions\LockerTransferPivot;
use App\ModelsMoneyPermissions\BankReceivePermission;
use App\ModelsMoneyPermissions\LockerReceivePermission;

class CommonLogic {
    function builde_search_form($form_for = 'locker' ,$back_link) {
        $employees = EmployeeData::select('id' ,'name_ar' ,'name_en')->get();
        $money_source_collection = $form_for == 'locker' ?
            Locker::select('id' ,'name_en' ,'name_ar')->get() : Account::select('id' ,'name_en' ,'name_ar')->get();
        $statuses = [
            'pending' => __('words.pending'),
            'approved' => __('words.approved'),
            'rejected' => __('words.rejected'),
        ];
        $code = view('admin.money-permissions.form-component' ,[
            'employees' => $employees,
            'statuses' => $statuses,
            'source_collection' => $money_source_collection,
            'back_link' => $back_link
        ])->render();
        return $code;
    }

    function builde_receive_search_form($back_link) {
        $employees = EmployeeData::select('id' ,'name_ar' ,'name_en')->get();
        $statuses = [
            'pending' => __('words.pending'),
            'approved' => __('words.approved'),
            'rejected' => __('words.rejected'),
        ];
        $code = view('admin.money-permissions.receive-form-component' ,[
            'employees' => $employees,
            'statuses' => $statuses,
            'back_link' => $back_link
        ])->render();
        return $code;
    }

    function fetch_source_money(Model $model ,$branch_id = NULL) {
        return $model->select('id' ,'name_en' ,'name_ar' ,'balance')->when($branch_id ,function ($q) use ($branch_id) {
            $q->where('branch_id' ,$branch_id);
        })->get();
    }

    function account_relation_exists($type = 'exchange' ,$source = 'locker') {
        $account_relation = NULL;
        if ($type == 'exchange' && $source == 'locker') {
            $account_relation = AccountRelationLogic::get_locker_exchange_permission();
        } else if ($type == 'receive' && $source == 'locker') {
            $account_relation = AccountRelationLogic::get_locker_receive_permission();
        } else if ($type == 'exchange' && $source == 'bank') {
            $account_relation = AccountRelationLogic::get_bank_exchange_permission();
        } else if ($type == 'receive' && $source == 'bank') {
            $account_relation = AccountRelationLogic::get_bank_receive_permission();
        }

        if ($account_relation) return $account_relation;
        throw new Exception(__('words.account-relation-not-exists'));
    }

    function get_locker_account_tree(Locker $locker) {
        $locker_account_tree = AccountRelationLogic::get_locker_account_tree($locker->id);
        if ($locker_account_tree) return $locker_account_tree;
        throw new Exception(__('words.locker-not-related-to-account'));
    }

    function get_bank_account_tree(Account $bank) {
        $bank_account_tree = AccountRelationLogic::get_bank_account_tree($bank->id);
        if ($bank_account_tree) return $bank_account_tree;
        throw new Exception(__('words.locker-not-related-to-account'));
    }

    static function create_locker_transfer(LockerReceivePermission $locker_receive) {
        $locker_transfer_id = LockerTransfer::create([
            'branch_id' => $locker_receive->exchange_permission->branch_id,
            'locker_from_id' => $locker_receive->exchange_permission->from_locker_id,
            'locker_to_id' => $locker_receive->exchange_permission->to_locker_id,
            'created_by' => auth()->user()->id,
            'date' => $locker_receive->operation_date,
            'amount' => $locker_receive->exchange_permission->amount,
            'description' => $locker_receive->exchange_permission->note .' ,'. $locker_receive->note,
        ])->id;
        LockerTransferPivot::create([
            'locker_transfer_id' => $locker_transfer_id,
            'locker_exchange_permission_id' => $locker_receive->exchange_permission->id,
            'locker_receive_permission_id' => $locker_receive->id
        ]);
    }

    static function create_bank_transfer(BankReceivePermission $bank_receive) {
        $bank_transfer_id = AccountTransfer::create([
            'branch_id' => $bank_receive->exchange_permission->branch_id,
            'account_from_id' => $bank_receive->exchange_permission->from_bank_id,
            'account_to_id' => $bank_receive->exchange_permission->to_bank_id,
            'created_by' => auth()->user()->id,
            'date' => $bank_receive->operation_date,
            'amount' => $bank_receive->exchange_permission->amount,
            'description' => $bank_receive->exchange_permission->note .' ,'. $bank_receive->note,
        ])->id;
        BankTransferPivot::create([
            'bank_transfer_id' => $bank_transfer_id,
            'bank_exchange_permission_id' => $bank_receive->exchange_permission->id,
            'bank_receive_permission_id' => $bank_receive->id
        ]);
    }

    // this function means withdraw from bank & deposit to locker
    static function create_bank_locker_transaction(LockerReceivePermission $locker_receive) {
        $data = [
            'locker_id' => $locker_receive->bank_exchange_permission->toLocker->id,
            'account_id' => $locker_receive->bank_exchange_permission->fromBank->id,
            'type' => 'deposit',
            'created_by' => auth()->user()->id,
            'date' => $locker_receive->operation_date,
            'amount' => $locker_receive->amount,
            'branch_id' => $locker_receive->branch_id
        ];
        LockerTransaction::create($data);
    }

    // this function means withdraw from bank & deposit to locker
    static function create_locker_bank_transaction(BankReceivePermission $bank_receive) {
        $data = [
            'locker_id' => $bank_receive->locker_exchange_permission->fromLocker->id,
            'account_id' => $bank_receive->locker_exchange_permission->toBank->id,
            'type' => 'withdrawal',
            'created_by' => auth()->user()->id,
            'date' => $bank_receive->operation_date,
            'amount' => $bank_receive->amount,
            'branch_id' => $bank_receive->branch_id
        ];
        LockerTransaction::create($data);
    }
}