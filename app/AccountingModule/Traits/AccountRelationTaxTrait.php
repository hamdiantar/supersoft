<?php
namespace App\AccountingModule\Traits;

use App\AccountingModule\Models\AccountRelation;
use App\AccountingModule\Models\AccountRelations\TaxesRelated;
use App\Http\Requests\AccountingModule\AccountRelations\TaxesRequest;
use App\Models\TaxesFees;
use Exception;

trait AccountRelationTaxTrait {
    private function taxes_related_model_name() {
        return '\App\AccountingModule\Models\AccountRelations\TaxesRelated';
    }

    private function get_branch_taxes() {
        return TaxesFees::where('branch_id' ,auth()->user()->branch_id)->select('id' ,'name_en' ,'name_ar' ,'branch_id')->get();
    }

    protected function create_taxes_related() {
        return view($this->main_view_path.'.create' ,[
            'action_for' => 'taxes',
            'view_path' => $this->main_view_path.'.create.taxes-form',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\TaxesRequest',
            'form_id' => '#account-relations-taxes-form',
            'root_accounts_tree' => $this->get_root_accounts(),
            'form_route' => route('account-relations.store-taxes'),
            'taxes' => $this->get_branch_taxes()
        ]);
    }

    function store_taxes_related(TaxesRequest $request) {
        $account_relation = $this->create_acc_relation($request ,$this->taxes_related_model_name());
        TaxesRelated::create(['tax_id' => $request->tax_id, 'account_relation_id' => $account_relation->id]);
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-created') ,'alert-type' => 'success'
        ]);
    }

    protected function relation_taxes_related_unique() {
        $data = request()->all();
        if (
            isset($data['tax_id']) && isset($data['accounts_tree_root_id']) && isset($data['accounts_tree_id'])
        ) {
            $tax_related = TaxesRelated::where(['tax_id' => $data['tax_id']])->first();
            if ($tax_related && isset($data['old_id']) && $tax_related->account_relation_id == $data['old_id'])
                return response(['message' => __('accounting-module.relation-not-exists-before')]);
            if ($tax_related) return response(['message' => __('accounting-module.relation-exists-before')] ,400);
            return response(['message' => __('accounting-module.relation-not-exists-before')]);
        }
        return response(['message' => __('accounting-module.please-fill-form')] ,400);
    }

    protected function edit_taxes_related(AccountRelation $account_relation) {
        return view($this->main_view_path.'.edit' ,[
            'model' => $account_relation,
            'form_route' => route('account-relations.update-taxes' ,['id' => $account_relation->id]),
            'taxes' => $this->get_branch_taxes(),
            'root_accounts_tree' => $this->get_root_accounts(),
            'accounts_tree' => $this->get_tree_accounts($account_relation->account_tree_root),
            'view_path' => $this->main_view_path.'.edit.taxes-edit',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\TaxesRequest',
            'form_id' => '#account-relations-taxes-form',
        ]);
    }

    function update_taxes_related(TaxesRequest $request ,$id) {
        try {
            $tax_relation = $this->update_acc_relateion($request ,$id);
        } catch (Exception $e) {
            return redirect(route('account-relations.index'))->with([
                'message' => __('accounting-module.data-not-found') ,'alert-type' => 'error'
            ]);
        }
        $tax_relation->related_taxes->update(['tax_id' => $request->tax_id]);
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-updated') ,'alert-type' => 'success'
        ]);
    }
}