<?php
namespace App\AccountingModule\Traits;

use App\AccountingModule\Models\AccountRelation;
use App\AccountingModule\Models\AccountRelations\DiscountRelated;
use App\Http\Requests\AccountingModule\AccountRelations\DiscountRequest;
use Exception;

trait AccountRelationDiscountTrait {
    private function discounts_related_model_name() {
        return '\App\AccountingModule\Models\AccountRelations\DiscountRelated';
    }

    protected function create_discounts_related() {
        return view($this->main_view_path.'.create' ,[
            'action_for' => 'discounts',
            'view_path' => $this->main_view_path.'.create.discounts-form',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\DiscountRequest',
            'form_id' => '#account-relations-discounts-form',
            'root_accounts_tree' => $this->get_root_accounts(),
            'form_route' => route('account-relations.store-discounts')
        ]);
    }

    function store_discounts_related(DiscountRequest $request) {
        $account_relation = $this->create_acc_relation($request ,$this->discounts_related_model_name());
        DiscountRelated::create(['discount_type' => $request->discount_type, 'account_relation_id' => $account_relation->id]);
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-created') ,'alert-type' => 'success'
        ]);
    }

    protected function relation_discounts_related_unique() {
        $data = request()->all();
        if (
            isset($data['discount_type']) && isset($data['accounts_tree_root_id']) && isset($data['accounts_tree_id'])
        ) {
            $discount_related = DiscountRelated::where(['discount_type' => $data['discount_type']])->first();
            if ($discount_related && isset($data['old_id']) && $discount_related->account_relation_id == $data['old_id'])
                return response(['message' => __('accounting-module.relation-not-exists-before')]);
            if ($discount_related) return response(['message' => __('accounting-module.relation-exists-before')] ,400);
            return response(['message' => __('accounting-module.relation-not-exists-before')]);
        }
        return response(['message' => __('accounting-module.please-fill-form')] ,400);
    }

    protected function edit_discounts_related(AccountRelation $account_relation) {
        return view($this->main_view_path.'.edit' ,[
            'model' => $account_relation,
            'form_route' => route('account-relations.update-discounts' ,['id' => $account_relation->id]),
            'root_accounts_tree' => $this->get_root_accounts(),
            'accounts_tree' => $this->get_tree_accounts($account_relation->account_tree_root),
            'view_path' => $this->main_view_path.'.edit.discounts-edit',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\DiscountRequest',
            'form_id' => '#account-relations-discounts-form',
        ]);
    }

    function update_discounts_related(DiscountRequest $request ,$id) {
        try {
            $discount_relation = $this->update_acc_relateion($request ,$id);
        } catch (Exception $e) {
            return redirect(route('account-relations.index'))->with([
                'message' => __('accounting-module.data-not-found') ,'alert-type' => 'error'
            ]);
        }
        $discount_relation->related_discounts->update(['discount_type' => $request->discount_type]);
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-updated') ,'alert-type' => 'success'
        ]);
    }
}