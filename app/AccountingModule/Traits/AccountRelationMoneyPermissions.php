<?php
namespace App\AccountingModule\Traits;

use Exception;
use App\AccountingModule\Models\AccountRelation;
use App\AccountingModule\Models\AccountRelations\MoneyPermissionsRelated;
use App\Http\Requests\AccountingModule\AccountRelations\MoneyPermissionRequest;

trait AccountRelationMoneyPermissions {
    private function money_permission_model_name() {
        return '\App\AccountingModule\Models\AccountRelations\MoneyPermissionsRelated';
    }

    protected function create_money_permission() {
        return view($this->main_view_path.'.create' ,[
            'action_for' => 'money-permissions',
            'view_path' => $this->main_view_path.'.create.money-permissions-form',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\MoneyPermissionRequest',
            'form_id' => '#account-relations-money-permissions-form',
            'form_route' => route('account-relations.store-money-permissions'),
            'root_accounts_tree' => $this->get_root_accounts()
        ]);
    }

    function store_money_permission(MoneyPermissionRequest $request) {
        $account_relation = $this->create_acc_relation($request ,$this->money_permission_model_name());
        MoneyPermissionsRelated::create([
            'money_gateway' => $request->account_nature == 'permission_locker' ? 'locker' : 'bank',
            'act_as' => $request->permission_nature == 'exchange' ? 'exchange' : 'receive',
            'account_relation_id' => $account_relation->id
        ]);
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-created') ,'alert-type' => 'success'
        ]);
    }

    protected function relation_money_permission_unique() {
        $data = request()->all();
        if (
            isset($data['permission_nature']) && isset($data['account_nature']) &&
            isset($data['accounts_tree_root_id']) && isset($data['accounts_tree_id'])
        ) {
            $money_gateway = $data['account_nature'] == 'permission_locker' ? 'locker' : 'bank';
            $money_permission_exists = MoneyPermissionsRelated::where([
                'money_gateway' => $money_gateway,
                'act_as' => $data['permission_nature'] == 'exchange' ? 'exchange' : 'receive'
            ])->first();
            if ($money_permission_exists && isset($data['old_id']) && $money_permission_exists->account_relation_id == $data['old_id'])
                return response(['message' => __('accounting-module.relation-not-exists-before')]);
            if ($money_permission_exists) {
                return response(['message' => __('accounting-module.relation-exists-as-'.$money_gateway)], 400);
            }
            return response(['message' => __('accounting-module.relation-not-exists-before')]);
        }
        return response(['message' => __('accounting-module.please-fill-form')] ,400);
    }

    protected function edit_money_permission(AccountRelation $account_relation) {
        return view($this->main_view_path.'.edit' ,[
            'model' => $account_relation,
            'form_route' => route('account-relations.update-money-permissions' ,['id' => $account_relation->id]),
            'root_accounts_tree' => $this->get_root_accounts(),
            'accounts_tree' => $this->get_tree_accounts($account_relation->account_tree_root),
            'view_path' => $this->main_view_path.'.edit.money-permission-edit',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\MoneyPermissionRequest',
            'form_id' => '#account-relations-money-permissions-form'
        ]);
    }

    function update_money_permission(MoneyPermissionRequest $request ,$id) {
        try {
            $money_permission_relation = $this->update_acc_relateion($request ,$id);
        } catch (Exception $e) {
            return redirect(route('account-relations.index'))->with([
                'message' => __('accounting-module.data-not-found') ,'alert-type' => 'error'
            ]);
        }
        $money_permission_relation->related_money_permission->update([
            'money_gateway' => $request->account_nature == 'permission_locker' ? 'locker' : 'bank',
            'act_as' => $request->permission_nature == 'exchange' ? 'exchange' : 'receive',
        ]);
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-updated') ,'alert-type' => 'success'
        ]);
    }
}