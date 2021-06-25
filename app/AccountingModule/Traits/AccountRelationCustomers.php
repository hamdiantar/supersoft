<?php
namespace App\AccountingModule\Traits;

use App\AccountingModule\Models\AccountRelation;
use App\AccountingModule\Models\AccountRelations\ActorsRelated;
use App\Http\Requests\AccountingModule\AccountRelations\CustomerRequest;
use App\Models\Customer;
use App\Models\CustomerCategory;
use Exception;

trait AccountRelationCustomers {
    private function customers_model_name() {
        return '\App\AccountingModule\Models\AccountRelations\ActorsRelated';
    }

    protected function create_customers() {
        return view($this->main_view_path.'.create' ,[
            'action_for' => 'customers',
            'view_path' => $this->main_view_path.'.create.customers-form',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\CustomerRequest',
            'form_id' => '#account-relations-customers-form',
            'script_file_path' => $this->main_view_path.'.create.actor-common-script',
            'root_accounts_tree' => $this->get_root_accounts(),
            'form_route' => route('account-relations.store-customers'),
            'customers' => $this->fetch_actors(new Customer),
            'groups' => $this->fetch_actors(new CustomerCategory)
        ]);
    }

    function store_customers(CustomerRequest $request) {
        $related_id = '0';
        if ($request->related_as == 'actor_group') $related_id = $request->group_id;
        else if ($request->related_as == 'actor_id') $related_id = $request->customer_id;
        $this->create_actor_relation(
            $this->customers_model_name(),
            'customer',
            $related_id
        );
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-created') ,'alert-type' => 'success'
        ]);
    }

    protected function relation_customers_unique() {
        $data = request()->all();
        if (
            isset($data['related_as']) && isset($data['accounts_tree_root_id']) && isset($data['accounts_tree_id'])
        ) {
            $where = ['actor_type' => 'customer' ,'related_as' => $data['related_as']];
            if (isset($data['customer_id'])) $where['related_id'] = $data['customer_id'];
            $customer_related = ActorsRelated::where($where)->first();
            if ($customer_related && isset($data['old_id']) && $customer_related->account_relation_id == $data['old_id'])
                return response(['message' => __('accounting-module.relation-not-exists-before')]);
            if ($customer_related) return response(['message' => __('accounting-module.relation-exists-before')] ,400);
            return response(['message' => __('accounting-module.relation-not-exists-before')]);
        }
        return response(['message' => __('accounting-module.please-fill-form')] ,400);
    }

    protected function edit_customers(AccountRelation $account_relation) {
        return view($this->main_view_path.'.edit' ,[
            'model' => $account_relation,
            'form_id' => '#account-relations-customers-form',
            'form_route' => route('account-relations.update-customers' ,['id' => $account_relation->id]),
            'groups' => $this->fetch_actors(new CustomerCategory),
            'customers' => $this->fetch_actors(new Customer),
            'root_accounts_tree' => $this->get_root_accounts(),
            'accounts_tree' => $this->get_tree_accounts($account_relation->account_tree_root),
            'view_path' => $this->main_view_path.'.edit.customer-edit',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\CustomerRequest',
            'script_file_path' => $this->main_view_path.'.create.actor-common-script'
        ]);
    }

    function update_customers(CustomerRequest $request ,$id) {
        $related_id = NULL;
        if ($request->related_as == 'actor_group') $related_id = $request->group_id;
        else if ($request->related_as == 'actor_id') $related_id = $request->customer_id;
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