<?php
namespace App\AccountingModule\Traits;

use Exception;
use App\AccountingModule\Models\AccountRelation;
use App\AccountingModule\Models\AccountRelations\StoresRelated;
use App\Http\Requests\AccountingModule\AccountRelations\StoresRelationRequest;
use App\Models\Store;

trait AccountRelationStoresTrait {
    private function stores_model_name() {
        return '\App\AccountingModule\Models\AccountRelations\StoresRelated';
    }

    protected function create_stores_relation() {
        return view($this->main_view_path.'.create' ,[
            'action_for' => 'stores',
            'view_path' => $this->main_view_path.'.create.stores-form',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\StoresRelationRequest',
            'form_id' => '#account-relations-stores-form',
            'script_file_path' => $this->main_view_path.'.create.stores-script',
            'form_route' => route('account-relations.store-stores'),
            'root_accounts_tree' => $this->get_root_accounts(),
            'stores' => Store::select('id' ,'name_ar' ,'name_en')->get()
        ]);
    }

    function store_stores_relation(StoresRelationRequest $request) {
        $account_relation = $this->create_acc_relation($request ,$this->stores_model_name());
        StoresRelated::create([
            'related_as' => $request->related_as,
            'related_id' => $request->related_as == 'store' ? $request->related_id : auth()->user()->branch_id,
            'account_relation_id' => $account_relation->id
        ]);
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-created') ,'alert-type' => 'success'
        ]);
    }

    protected function relation_stores_relation_unique() {
        $data = request()->all();
        if (
            isset($data['related_as']) && isset($data['accounts_tree_root_id']) && isset($data['accounts_tree_id'])
        ) {
            if ($data['related_as'] != 'store' || ($data['related_as'] == 'store' && isset($data['related_id']))) {
                $stores_relation_exists = StoresRelated::where([
                    'related_as' => $data['related_as'],
                    'related_id' => $data['related_as'] == 'store' ? $data['related_id'] : auth()->user()->branch_id,
                ])->first();
                if ($stores_relation_exists && isset($data['old_id']) && $stores_relation_exists->account_relation_id == $data['old_id']) {
                    return response(['message' => __('accounting-module.relation-not-exists-before')]);
                }
                if ($stores_relation_exists) {
                    return response(['message' => __('accounting-module.relation-exists-before')], 400);
                }
                return response(['message' => __('accounting-module.relation-not-exists-before')]);
            }
        }
        return response(['message' => __('accounting-module.please-fill-form')] ,400);
    }

    protected function edit_stores_relation(AccountRelation $account_relation) {
        return view($this->main_view_path.'.edit' ,[
            'model' => $account_relation,
            'form_route' => route('account-relations.update-stores' ,['id' => $account_relation->id]),
            'root_accounts_tree' => $this->get_root_accounts(),
            'accounts_tree' => $this->get_tree_accounts($account_relation->account_tree_root),
            'view_path' => $this->main_view_path.'.edit.stores-edit',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\StoresRelationRequest',
            'script_file_path' => $this->main_view_path.'.create.stores-script',
            'form_id' => '#account-relations-stores-form',
            'stores' => Store::select('id' ,'name_ar' ,'name_en')->get()
        ]);
    }

    function update_stores_relation(StoresRelationRequest $request ,$id) {
        try {
            $stores_relation = $this->update_acc_relateion($request ,$id);
        } catch (Exception $e) {
            return redirect(route('account-relations.index'))->with([
                'message' => __('accounting-module.data-not-found') ,'alert-type' => 'error'
            ]);
        }
        $stores_relation->related_store->update([
            'related_as' => $request->related_as,
            'related_id' => $request->related_as == 'store' ? $request->related_id : auth()->user()->branch_id
        ]);
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-updated') ,'alert-type' => 'success'
        ]);
    }
}