<?php
namespace App\AccountingModule\ExportCore;

class TrialBalancePrinter extends AbstractExportable {
    const view_path = 'accounting-module.trial-balance.printer';

    protected $totals;
    protected $show_transactions = false;

    function set_show_transactions($show_transactions) {
        $this->show_transactions = $show_transactions;
    }

    function set_totals($totals) {
        $this->totals = $totals;
    }

    function invoke_export() {
        $collection = $this->collection;
        $title = __('accounting-module.trial-balance-index');
        $layout_path = $this->common_printer_layout;
        $table_header = $this->columns;
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
        $total = $this->totals;

        return view(self::view_path . '.trial-balance' ,
            compact('title' ,'layout_path' ,'collection' ,'hidden_columns' ,'table_header' ,'total')
        );
    }
}