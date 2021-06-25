<?php

namespace App\Observers;

use Exception;
use App\Models\RevenueReceipt;
use App\AccountingModule\Models\FiscalYear;
use App\AccountingModule\Models\AccountsTree;
use App\AccountingModule\Models\AccountRelation;
use App\AccountingModule\Models\DailyRestriction;
use App\AccountingModule\Controllers\CostCenterCont;
use App\AccountingModule\CoreLogic\AccountRelationLogic;
use App\AccountingModule\Models\DailyRestrictionTable;

class RevenueReceiptObserver
{
    /**
     * Handle the revenue receipt "created" event.
     *
     * @param  \App\Models\RevenueReceipt  $revenueReceipt
     * @return void
     */
    public function created(RevenueReceipt $revenueReceipt)
    {
        $daily_restriction = $this->create_daily_restriction($revenueReceipt);
        $this->create_daily_restriction_row($daily_restriction, $revenueReceipt);
    }

    /**
     * Handle the revenue receipt "updated" event.
     *
     * @param  \App\Models\RevenueReceipt  $revenueReceipt
     * @return void
     */
    public function updated(RevenueReceipt $revenueReceipt)
    {
        $daily_restriction = DailyRestriction::where([
            'reference_type' => '\App\Models\RevenueReceipt',
            'reference_id' => $revenueReceipt->id
        ])->first();
        if ($daily_restriction) {
            $daily_restriction->my_table()->delete();
            $daily_restriction->update([
                'operation_date' => $revenueReceipt->date,
                'credit_amount' => $revenueReceipt->cost,
                'debit_amount' => $revenueReceipt->cost,
                'branch_id' => $revenueReceipt->branch_id
            ]);
        }
        if (!$daily_restriction) $daily_restriction = $this->create_daily_restriction($revenueReceipt);
        $this->create_daily_restriction_row($daily_restriction, $revenueReceipt);
    }

    /**
     * Handle the revenue receipt "deleted" event.
     *
     * @param  \App\Models\RevenueReceipt  $revenueReceipt
     * @return void
     */
    public function deleted(RevenueReceipt $revenueReceipt)
    {
        $daily_restriction = DailyRestriction::where([
            'reference_type' => '\App\Models\RevenueReceipt',
            'reference_id' => $revenueReceipt->id
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
            'reference_type' => '\App\Models\RevenueReceipt',
            'reference_id' => $bill->id,
            'branch_id' => $bill->branch_id
        ]);
    }

    private function create_daily_restriction_row($daily_restriction, $bill) {
        $account = AccountRelationLogic::get_credit_account_tree($bill->revenue_type_id ,$bill->revenue_item_id);
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

        $account = $bill->deportation == 'safe' ?
            AccountRelationLogic::get_locker_account_tree($bill->locker_id)
            :
            AccountRelationLogic::get_bank_account_tree($bill->account_id);
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
    }
}
