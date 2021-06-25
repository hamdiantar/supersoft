<?php
namespace App\AccountingModule\Traits;

use Exception;
use App\AccountingModule\Models\ExpenseItems;
use App\AccountingModule\Models\ExpenseTypes;
use App\AccountingModule\Models\RevenueItems;
use App\AccountingModule\Models\RevenueTypes;
use App\AccountingModule\Models\AccountRelation;
use App\AccountingModule\Models\AccountRelations\ExpensesRevenuesRelated;
use App\Http\Requests\AccountingModule\AccountRelations\TypesItemsRequest;

trait AccountRelationTypesItems {
    private function types_items_model_name() {
        return '\App\AccountingModule\Models\AccountRelations\ExpensesRevenuesRelated';
    }

    protected function create_types_items() {
        return view($this->main_view_path.'.create' ,[
            'action_for' => 'types-items',
            'view_path' => $this->main_view_path.'.create.types-items-form',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\TypesItemsRequest',
            'form_id' => '#account-relations-types-items-form',
            'script_file_path' => $this->main_view_path.'.create.types-items-script',
            'root_accounts_tree' => $this->get_root_accounts(),
            'credit_revenues' => RevenueTypes::all(),
            'debit_expenses' => ExpenseTypes::all(),
            'credit_revenues_items' => RevenueItems::all(),
            'debit_expenses_items' => ExpenseItems::all(),
            'form_route' => route('account-relations.store-types-items')
        ]);
    }

    function store_types_items(TypesItemsRequest $request) {
        $account_relation = $this->create_acc_relation($request ,$this->types_items_model_name());
        ExpensesRevenuesRelated::create([
            'account_relation_id' => $account_relation->id,
            'related_as' => $request->account_nature == 'credit' ? 'credit' : 'debit',
            'type_id' => $request->account_type_id,
            'item_id' => $request->account_item_id
        ]);
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-created') ,'alert-type' => 'success'
        ]);
    }

    protected function relation_types_items_unique() {
        $data = request()->all();
        if (
            isset($data['account_type_id']) && isset($data['account_item_id']) && isset($data['account_nature']) &&
            isset($data['accounts_tree_root_id']) && isset($data['accounts_tree_id'])
        ) {
            $exists = AccountRelation::
            join('expenses_revenues_related' ,'account_relations.id' ,'=' ,'expenses_revenues_related.account_relation_id')
            ->where('account_relations.accounts_tree_root_id' ,$data['accounts_tree_root_id'])
            ->where('account_relations.accounts_tree_id' ,$data['accounts_tree_id'])
            ->where('account_relations.related_model_name' ,$this->types_items_model_name())
            ->where('expenses_revenues_related.type_id' ,$data['account_type_id'])
            ->where('expenses_revenues_related.item_id' ,$data['account_item_id'])
            ->where('expenses_revenues_related.related_as' ,$data['account_nature'] == 'credit' ? 'credit' : 'debit')
            ->when(request()->has('old_id') ,function ($q) {
                $q->where('account_relations.id' ,'!=' ,request('old_id'));
            })
            ->first();
            if ($exists) return response(['message' => __('accounting-module.relation-exists-before')] ,400);
            return response(['message' => __('accounting-module.relation-not-exists-before')]);
        }
        return response(['message' => __('accounting-module.please-fill-form')] ,400);
    }

    protected function edit_types_items(AccountRelation $account_relation) {
        return view($this->main_view_path.'.edit' ,[
            'model' => $account_relation,
            'form_route' => route('account-relations.update-types-items' ,['id' => $account_relation->id]),
            'root_accounts_tree' => $this->get_root_accounts(),
            'view_path' => $this->main_view_path.'.edit.types-items',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\TypesItemsRequest',
            'script_file_path' => $this->main_view_path.'.edit.types-items-script',
            'form_id' => '#account-relations-types-items-form',
            'credit_revenues' => RevenueTypes::all(),
            'credit_revenues_items' => RevenueItems::all(),
            'debit_expenses' => ExpenseTypes::all(),
            'debit_expenses_items' => ExpenseItems::all(),
            'without_main_script' => true
        ]);
    }

    function update_types_items(TypesItemsRequest $request ,$id) {
        try {
            $type_item_relation = $this->update_acc_relateion($request ,$id);
        } catch (Exception $e) {
            return redirect(route('account-relations.index'))->with([
                'message' => __('accounting-module.data-not-found') ,'alert-type' => 'error'
            ]);
        }
        $type_item_relation->related_expense_revenue->update([
            'related_as' => $request->account_nature == 'credit' ? 'credit' : 'debit',
            'type_id' => $request->account_type_id,
            'item_id' => $request->account_item_id
        ]);
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-updated') ,'alert-type' => 'success'
        ]);
    }
}