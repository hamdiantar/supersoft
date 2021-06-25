<?php

namespace App\Observers;

use Exception;
use App\Models\ExpensesReceipt;
use App\AccountingModule\Models\FiscalYear;
use App\AccountingModule\Models\DailyRestriction;
use App\AccountingModule\Controllers\CostCenterCont;
use App\AccountingModule\CoreLogic\AccountRelationLogic;
use App\AccountingModule\Models\DailyRestrictionTable;

class ExpensesReceiptObserver
{
    /**
     * Handle the expenses receipt "created" event.
     *
     * @param  \App\Models\ExpensesReceipt  $expensesReceipt
     * @return void
     */
    public function created(ExpensesReceipt $expensesReceipt)
    {
        $daily_restriction = $this->create_daily_restriction($expensesReceipt);
        $this->create_daily_restriction_row($daily_restriction, $expensesReceipt);
    }

    /**
     * Handle the expenses receipt "updated" event.
     *
     * @param  \App\Models\ExpensesReceipt  $expensesReceipt
     * @return void
     */
    public function updated(ExpensesReceipt $expensesReceipt)
    {
        $daily_restriction = DailyRestriction::where([
            'reference_type' => '\App\Models\ExpensesReceipt',
            'reference_id' => $expensesReceipt->id
        ])->first();
        if ($daily_restriction) {
            $daily_restriction->my_table()->delete();
            $daily_restriction->update([
                'operation_date' => $expensesReceipt->date,
                'debit_amount' => $expensesReceipt->cost,
                'credit_amount' => $expensesReceipt->cost,
                'branch_id' => $expensesReceipt->branch_id
            ]);
        }
        if (!$daily_restriction) $daily_restriction = $this->create_daily_restriction($expensesReceipt);
        $this->create_daily_restriction_row($daily_restriction, $expensesReceipt);
    }

    /**
     * Handle the expenses receipt "deleted" event.
     *
     * @param  \App\Models\ExpensesReceipt  $expensesReceipt
     * @return void
     */
    public function deleted(ExpensesReceipt $expensesReceipt)
    {
        $daily_restriction = DailyRestriction::where([
            'reference_type' => '\App\Models\ExpensesReceipt',
            'reference_id' => $expensesReceipt->id
        ])->first();
        if ($daily_restriction) {
            $daily_restriction->my_table()->delete();
            $daily_restriction->delete();
        }
    }

    private function create_daily_restriction($bill) {
        $fiscal = FiscalYear::where('status' ,1)->first();
        $restriction_number = date('y') . '0000';
        $operation_number = 1;

        $last_restriction = DailyRestriction::orderBy('id' ,'desc')->first();
        if ($last_restriction) {
            $restriction_number .= ($last_restriction->id + 1);
            try {
                $operation_number = $$last_restriction->operation_number + 1;
            } catch (Exception $e) {}
        } else $restriction_number .= '1';

        return DailyRestriction::create([
            'restriction_number' => $restriction_number,
            'operation_number' => $operation_number,
            'operation_date' => $bill->date,
            'debit_amount' => $bill->cost,
            'credit_amount' => $bill->cost,
            'records_number' => 2,
            'auto_generated' => 1,
            'fiscal_year_id' => $fiscal ? $fiscal->id : NULL,
            'is_adverse' => 0,
            'reference_type' => '\App\Models\ExpensesReceipt',
            'reference_id' => $bill->id,
            'branch_id' => $bill->branch_id
        ]);
    }

    private function create_daily_restriction_row($daily_restriction, $bill) {
        $account = AccountRelationLogic::get_debit_account_tree($bill->expense_type_id ,$bill->expense_item_id);
        $table_row = [
            'daily_restriction_id' => $daily_restriction->id,
            'accounts_tree_id' => $account ? $account->id : NULL,
            'debit_amount' => $bill->cost,
            'credit_amount' => 0,
            'account_tree_code' => $account ? $account->code : NULL,
            'cost_center_code' => CostCenterCont::get_my_code($bill->cost_center_id),
            'cost_center_id' => $bill->cost_center_id,
        ];
        DailyRestrictionTable::create($table_row);

        $account = $bill->deportation == 'safe' ?
            AccountRelationLogic::get_locker_account_tree($bill->locker_id)
            :
            AccountRelationLogic::get_bank_account_tree($bill->account_id);
        $table_row = [
            'daily_restriction_id' => $daily_restriction->id,
            'accounts_tree_id' => $account ? $account->id : NULL,
            'debit_amount' => 0,
            'credit_amount' => $bill->cost,
            'account_tree_code' => $account ? $account->code : NULL,
            'cost_center_code' => CostCenterCont::get_my_code($bill->cost_center_id),
            'cost_center_id' => $bill->cost_center_id,
        ];
        DailyRestrictionTable::create($table_row);
    }
}
