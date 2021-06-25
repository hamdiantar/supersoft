<?php
namespace App\AccountingModule\Traits;

use Exception;
use App\AccountingModule\Models\AccountRelation;
use App\AccountingModule\Models\AccountRelations\StorePermissionRelated;
use App\Http\Requests\AccountingModule\AccountRelations\StoresPermissionRequest;

trait AccountRelationStorePermissions {
    private function stores_permission_model_name() {
        return '\App\AccountingModule\Models\AccountRelations\StorePermissionRelated';
    }

    protected function create_stores_permission() {
        return view($this->main_view_path.'.create' ,[
            'action_for' => 'stores-permissions',
            'view_path' => $this->main_view_path.'.create.stores-permissions-form',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\StoresPermissionRequest',
            'form_id' => '#account-relations-stores-permissions-form',
            'form_route' => route('account-relations.store-stores-permissions'),
            'root_accounts_tree' => $this->get_root_accounts()
        ]);
    }

    function store_stores_permission(StoresPermissionRequest $request) {
        $account_relation = $this->create_acc_relation($request ,$this->stores_permission_model_name());
        StorePermissionRelated::create([
            'permission_nature' => $request->permission_nature,
            'account_relation_id' => $account_relation->id
        ]);
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-created') ,'alert-type' => 'success'
        ]);
    }

    protected function relation_stores_permission_unique() {
        $data = request()->all();
        if (
            isset($data['permission_nature']) && isset($data['accounts_tree_root_id']) && isset($data['accounts_tree_id'])
        ) {
            $nature = $data['permission_nature'] == 'exchange' ? 'exchange' : 'receive';
            $stores_permission_exists = StorePermissionRelated::where([
                'permission_nature' => $nature
            ])->first();
            if ($stores_permission_exists && isset($data['old_id']) && $stores_permission_exists->account_relation_id == $data['old_id'])
                return response(['message' => __('accounting-module.relation-not-exists-before')]);
            if ($stores_permission_exists) {
                return response(['message' => __('accounting-module.relation-exists-as-store-'.$nature)], 400);
            }
            return response(['message' => __('accounting-module.relation-not-exists-before')]);
        }
        return response(['message' => __('accounting-module.please-fill-form')] ,400);
    }

    protected function edit_stores_permission(AccountRelation $account_relation) {
        return view($this->main_view_path.'.edit' ,[
            'model' => $account_relation,
            'form_route' => route('account-relations.update-stores-permissions' ,['id' => $account_relation->id]),
            'root_accounts_tree' => $this->get_root_accounts(),
            'accounts_tree' => $this->get_tree_accounts($account_relation->account_tree_root),
            'view_path' => $this->main_view_path.'.edit.stores-permission-edit',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\StoresPermissionRequest',
            'form_id' => '#account-relations-stores-permissions-form',
        ]);
    }

    function update_stores_permission(StoresPermissionRequest $request ,$id) {
        try {
            $stores_permission_relation = $this->update_acc_relateion($request ,$id);
        } catch (Exception $e) {
            return redirect(route('account-relations.index'))->with([
                'message' => __('accounting-module.data-not-found') ,'alert-type' => 'error'
            ]);
        }
        $stores_permission_relation->related_stores_permission->update([
            'permission_nature' => $request->permission_nature
        ]);
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-updated') ,'alert-type' => 'success'
        ]);
    }
}