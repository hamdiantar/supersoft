<?php
namespace App\AccountingModule\Traits;

use Exception;
use App\Models\Supplier;
use App\Models\SupplierGroup;
use App\AccountingModule\Models\AccountRelation;
use App\AccountingModule\Models\AccountRelations\ActorsRelated;
use App\Http\Requests\AccountingModule\AccountRelations\SupplierRequest;

trait AccountRelationSuppliers {
    private function suppliers_model_name() {
        return '\App\AccountingModule\Models\AccountRelations\ActorsRelated';
    }

    protected function create_suppliers() {
        return view($this->main_view_path.'.create' ,[
            'action_for' => 'suppliers',
            'view_path' => $this->main_view_path.'.create.suppliers-form',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\SupplierRequest',
            'form_id' => '#account-relations-suppliers-form',
            'script_file_path' => $this->main_view_path.'.create.actor-common-script',
            'root_accounts_tree' => $this->get_root_accounts(),
            'form_route' => route('account-relations.store-suppliers'),
            'suppliers' => $this->fetch_actors(new Supplier),
            'groups' => $this->fetch_actors(new SupplierGroup)
        ]);
    }

    function store_suppliers(SupplierRequest $request) {
        $related_id = '';
        if ($request->related_as == 'actor_group') $related_id = $request->group_id;
        else if ($request->related_as == 'actor_id') $related_id = $request->supplier_id;
        $this->create_actor_relation(
            $this->suppliers_model_name(),
            'supplier',
            $related_id
        );
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-created') ,'alert-type' => 'success'
        ]);
    }

    protected function relation_suppliers_unique() {
        $data = request()->all();
        if (
            isset($data['related_as']) && isset($data['accounts_tree_root_id']) && isset($data['accounts_tree_id'])
        ) {
            $where = ['actor_type' => 'supplier' ,'related_as' => $data['related_as']];
            if (isset($data['supplier_id'])) $where['related_id'] = $data['supplier_id'];
            $supplier_related = ActorsRelated::where($where)->first();
            if ($supplier_related && isset($data['old_id']) && $supplier_related->account_relation_id == $data['old_id'])
                return response(['message' => __('accounting-module.relation-not-exists-before')]);
            if ($supplier_related) return response(['message' => __('accounting-module.relation-exists-before')] ,400);
            return response(['message' => __('accounting-module.relation-not-exists-before')]);
        }
        return response(['message' => __('accounting-module.please-fill-form')] ,400);
    }

    protected function edit_suppliers(AccountRelation $account_relation) {
        return view($this->main_view_path.'.edit' ,[
            'model' => $account_relation,
            'form_route' => route('account-relations.update-suppliers' ,['id' => $account_relation->id]),
            'groups' => $this->fetch_actors(new SupplierGroup),
            'suppliers' => $this->fetch_actors(new Supplier),
            'root_accounts_tree' => $this->get_root_accounts(),
            'accounts_tree' => $this->get_tree_accounts($account_relation->account_tree_root),
            'view_path' => $this->main_view_path.'.edit.supplier-edit',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\SupplierRequest',
            'script_file_path' => $this->main_view_path.'.create.actor-common-script',
            'form_id' => '#account-relations-suppliers-form',
        ]);
    }

    function update_suppliers(SupplierRequest $request ,$id) {
        $related_id = NULL;
        if ($request->related_as == 'actor_group') $related_id = $request->group_id;
        else if ($request->related_as == 'actor_id') $related_id = $request->supplier_id;
        try {
            $this->update_actor_relation($request ,$id ,$related_id);
        } catch (Exception $e) {
            return redirect(route('account-relations.index'))->with([
                'message' => __('accounting-module.data-not-found') ,'alert-type' => 'error'
            ]);
        }
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-updated') ,'alert-type' => 'success'
        ]);
    }
}