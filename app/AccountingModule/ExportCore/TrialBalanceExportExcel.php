<?php
namespace App\AccountingModule\ExportCore;

use Excel;

class TrialBalanceExportExcel extends AbstractExportable {
    protected $show_transactions = false;

    function set_show_transactions($show_transactions) {
        $this->show_transactions = $show_transactions;
    }

    function invoke_export() {
        try {
            $hidden_columns = explode("," ,$this->hidden_columns);
            if ($this->show_transactions == false) {
                $hidden_columns[] = 'start-balance';
                $hidden_columns[] = 'account-nature';
                $hidden_columns[] = 'transactions-debit';
                $hidden_columns[] = 'transactions-credit';
            }
        } catch (\Exception $e) {
            $hidden_columns = NULL;
            if ($this->show_transactions == false) {
                $hidden_columns[] = 'start-balance';
                $hidden_columns[] = 'account-nature';
                $hidden_columns[] = 'transactions-debit';
                $hidden_columns[] = 'transactions-credit';
            }
        }
        if ($hidden_columns) {
            $columns = [];
            foreach($this->columns as $col) {
                if (!in_array(implode("-" ,explode("_" ,$col)) ,$hidden_columns)) {
                    $columns[] = $col;
                }
            }
        } else {
            $columns = $this->columns;
        }
        
        $export_object = new GlobalExporter($this->collection ,$columns);
        return Excel::download($export_object, 'trial-balance-'.date('YmdHis').'.xlsx');
    }
}