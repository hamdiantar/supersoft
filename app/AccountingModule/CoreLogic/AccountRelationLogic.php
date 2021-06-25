<?php
namespace App\AccountingModule\CoreLogic;

use App\AccountingModule\Models\AccountsTree;
use App\AccountingModule\Models\AccountRelation;

class AccountRelationLogic {
    static function get_expenses_revenues_accounts() {
        return AccountRelation::join('expenses_revenues_related' ,'expenses_revenues_related.account_relation_id' ,'=' ,'account_relations.id')
        ->whereNull('account_relations.deleted_at')->distinct()->pluck('accounts_tree_id')->toArray();
    }

    static function get_expenses_accounts($branch_id = NULL) {
        return AccountRelation::
            join('expenses_revenues_related' ,'expenses_revenues_related.account_relation_id' ,'=' ,'account_relations.id')
            ->whereNull('account_relations.deleted_at')
            ->where('expenses_revenues_related.related_as' ,'debit')
            ->when($branch_id ,function ($q) use ($branch_id) {
                $q->where('account_relations.branch_id' ,$branch_id);
            })
            ->distinct()
            ->pluck('accounts_tree_id')->toArray();
    }

    static function get_revenues_accounts($branch_id = NULL) {
        return AccountRelation::
            join('expenses_revenues_related' ,'expenses_revenues_related.account_relation_id' ,'=' ,'account_relations.id')
            ->whereNull('account_relations.deleted_at')
            ->where('expenses_revenues_related.related_as' ,'credit')
            ->when($branch_id ,function ($q) use ($branch_id) {
                $q->where('account_relations.branch_id' ,$branch_id);
            })
            ->distinct()
            ->pluck('accounts_tree_id')->toArray();
    }

    static function get_debit_account_tree($type_id ,$item_id) {
        return self::account_by_type_item($type_id ,$item_id ,'debit');
    }

    static function get_credit_account_tree($type_id ,$item_id) {
        return self::account_by_type_item($type_id ,$item_id);
    }

    static function get_bank_account_tree($bank_id) {
        return self::account_by_bank_locker($bank_id ,'bank');
    }

    static function get_locker_account_tree($locker_id) {
        return self::account_by_bank_locker($locker_id);
    }

    static function get_locker_receive_permission() {
        return self::money_permission_account('locker' ,'receive');
    }

    static function get_locker_exchange_permission() {
        return self::money_permission_account('locker' ,'exchange');
    }

    static function get_bank_receive_permission() {
        return self::money_permission_account('bank' ,'receive');
    }

    static function get_bank_exchange_permission() {
        return self::money_permission_account('bank' ,'exchange');
    }

    private static function account_by_type_item($type_id ,$item_id ,$action_for = 'credit') {
        $tree_id_by_type_item = self::fetch_relation_row('expenses_revenues_related' ,[
            'expenses_revenues_related.related_as' => $action_for,
            'expenses_revenues_related.type_id' => $type_id,
            'expenses_revenues_related.item_id' => $item_id,
        ]);
        return $tree_id_by_type_item ? AccountsTree::find($tree_id_by_type_item->account_id) : NULL;
    }

    private static function account_by_bank_locker($id ,$action_for = 'locker') {
        $tree_id_by_locker_bank = self::fetch_relation_row('lockers_banks_related' ,[
            'lockers_banks_related.related_as' => $action_for,
            'lockers_banks_related.related_id' => $id
        ]);
        return $tree_id_by_locker_bank ? AccountsTree::find($tree_id_by_locker_bank->account_id) : NULL;
        
    }

    private static function money_permission_account($action_for ,$act_as) {
        $tree_for_permission = self::fetch_relation_row('money_permissions_related' ,[
            'money_permissions_related.money_gateway' => $action_for,
            'money_permissions_related.act_as' => $act_as
        ]);
        return $tree_for_permission ? AccountsTree::find($tree_for_permission->account_id) : NULL;
    }

    private static function fetch_relation_row($aux_table ,$whereClause) {
        return AccountRelation::
        join($aux_table ,$aux_table.'.account_relation_id' ,'=' ,'account_relations.id')
        ->whereNull('account_relations.deleted_at')
        ->where($whereClause)
        ->select('account_relations.accounts_tree_id as account_id')
        ->first();
    }
}