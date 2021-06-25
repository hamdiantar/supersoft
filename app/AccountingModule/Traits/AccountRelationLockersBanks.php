<?php
namespace App\AccountingModule\Traits;

use Exception;
use App\AccountingModule\Models\AccountRelation;
use App\AccountingModule\Models\AccountRelations\LockersBanksRelated;
use App\Http\Requests\AccountingModule\AccountRelations\LockersBanksRequest;

trait AccountRelationLockersBanks {
    private function lockers_banks_model_name() {
        return '\App\AccountingModule\Models\AccountRelations\LockersBanksRelated';
    }

    protected function create_lockers_banks() {
        return view($this->main_view_path.'.create' ,[
            'action_for' => 'lockers-banks',
            'view_path' => $this->main_view_path.'.create.lockers-banks-form',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\LockersBanksRequest',
            'form_id' => '#account-relations-lockers-banks-form',
            'script_file_path' => $this->main_view_path.'.create.lockers-banks-script',
            'root_accounts_tree' => $this->get_root_accounts(),
            'form_route' => route('account-relations.store-lockers-banks')
        ]);
    }

    function store_lockers_banks(LockersBanksRequest $request) {
        $account_relation = $this->create_acc_relation($request ,$this->lockers_banks_model_name());
        LockersBanksRelated::create([
            'account_relation_id' => $account_relation->id,
            'related_as' => $request->account_nature == 'bank_acc' ? 'bank' : 'locker',
            'related_id' => $request->account_nature == 'bank_acc' ? $request->bank_id : $request->locker_id
        ]);
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-created') ,'alert-type' => 'success'
        ]);
    }

    protected function relation_lockers_banks_unique() {
        $data = request()->all();
        if (
            isset($data['account_nature']) && isset($data['accounts_tree_root_id']) && isset($data['accounts_tree_id']) &&
            (isset($data['locker_id']) || isset($data['bank_id']))
        ) {
            $locker_bank_exists = LockersBanksRelated::where([
                'related_as' => $data['account_nature'] == 'locker' ? 'locker' : 'bank',
                'related_id' => isset($data['locker_id']) ? $data['locker_id'] : $data['bank_id']
            ])->first();
            if ($locker_bank_exists && isset($data['old_id']) && $locker_bank_exists->account_relation_id == $data['old_id'])
                return response(['message' => __('accounting-module.relation-not-exists-before')]);
            if ($locker_bank_exists) return response(['message' => __('accounting-module.relation-exists-before')] ,400);
            return response(['message' => __('accounting-module.relation-not-exists-before')]);
        }
        return response(['message' => __('accounting-module.please-fill-form')] ,400);
    }

    protected function edit_lockers_banks(AccountRelation $account_relation) {
        return view($this->main_view_path.'.edit' ,[
            'model' => $account_relation,
            'form_route' => route('account-relations.update-lockers-banks' ,['id' => $account_relation->id]),
            'root_accounts_tree' => $this->get_root_accounts(),
            'accounts_tree' => $this->get_tree_accounts($account_relation->account_tree_root),
            'view_path' => $this->main_view_path.'.edit.lockers-banks-edit',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\LockersBanksRequest',
            'form_id' => '#account-relations-lockers-banks-form',
            'script_file_path' => $this->main_view_path.'.create.lockers-banks-script'
        ]);
    }

    function update_lockers_banks(LockersBanksRequest $request ,$id) {
        try {
            $locker_bank_relation = $this->update_acc_relateion($request ,$id);
        } catch (Exception $e) {
            return redirect(route('account-relations.index'))->with([
                'message' => __('accounting-module.data-not-found') ,'alert-type' => 'error'
            ]);
        }
        $locker_bank_relation->related_locker_bank->update([
            'related_as' => $request->account_nature == 'locker' ? 'locker' : 'bank',
            'related_id' => $request->account_nature == 'locker' ? $request->locker_id : $request->bank_id
        ]);
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-updated') ,'alert-type' => 'success'
        ]);
    }
}