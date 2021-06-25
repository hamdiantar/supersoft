<?php
namespace App\Http\Controllers\Admin\MoneyPermissions;

use Exception;
use App\AccountingModule\Models\FiscalYear;
use App\AccountingModule\Models\DailyRestriction;
use App\AccountingModule\Controllers\CostCenterCont;
use App\AccountingModule\Models\DailyRestrictionTable;

trait DailyRestrictionTrait {
    private function create_daily_restriction() {
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

        $model_name = "\\".get_class($this->money_permission);
        
        return DailyRestriction::create([
            'restriction_number' => $restriction_number,
            'operation_number' => $operation_number,
            'operation_date' => $this->money_permission->operation_date,
            'debit_amount' => $this->money_permission->amount,
            'credit_amount' => $this->money_permission->amount,
            'records_number' => 2,
            'auto_generated' => 1,
            'fiscal_year_id' => $fiscal ? $fiscal->id : NULL,
            'is_adverse' => 0,
            'reference_type' => $model_name,
            'reference_id' => $this->money_permission->id,
            'branch_id' => $this->money_permission->branch_id
        ]);
    }

    private function create_daily_restriction_rows($daily_restriction) {
        $table_row = [
            'daily_restriction_id' => $daily_restriction->id,
            'accounts_tree_id' => $this->money_permission_account->id,
            'debit_amount' => 0,
            'credit_amount' => 0,
            'account_tree_code' => $this->money_permission_account->code,
            'cost_center_code' => CostCenterCont::get_my_code($this->money_permission->cost_center_id),
            'cost_center_id' => $this->money_permission->cost_center_id,
            'notes' => $this->money_permission->note
        ];
        $key = $this->operation == StaticNames::EXCHANGE ? "debit_amount" : "credit_amount";
        $table_row[$key] = $this->money_permission->amount;
        DailyRestrictionTable::create($table_row);

        
        $table_row = [
            'daily_restriction_id' => $daily_restriction->id,
            'accounts_tree_id' => $this->source_account->id,
            'debit_amount' => 0,
            'credit_amount' => 0,
            'account_tree_code' => $this->source_account->code,
            'cost_center_code' => CostCenterCont::get_my_code($this->money_permission->cost_center_id),
            'cost_center_id' => $this->money_permission->cost_center_id,
            'notes' => $this->money_permission->note
        ];
        $key = $this->operation == StaticNames::EXCHANGE ? "credit_amount" : "debit_amount";
        $table_row[$key] = $this->money_permission->amount;
        DailyRestrictionTable::create($table_row);
    }
}