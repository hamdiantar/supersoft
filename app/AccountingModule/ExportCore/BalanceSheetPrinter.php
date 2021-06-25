<?php
namespace App\AccountingModule\ExportCore;

class BalanceSheetPrinter extends AbstractExportable {
    const view_path = 'accounting-module.balance-sheet.printer';

    protected $totals;

    function set_totals($totals) {
        $this->totals = $totals;
    }

    function invoke_export() {
        $collection = $this->collection;
        $title = __('accounting-module.balance-sheet-index');
        $layout_path = $this->common_printer_layout;
        $table_header = $this->columns;
        try {
            $hidden_columns = explode("," ,$this->hidden_columns);
        } catch (\Exception $e) {
            $hidden_columns = NULL;
        }
        $total = $this->totals;

        return view(self::view_path . '.balance-sheet' ,
            compact('title' ,'layout_path' ,'collection' ,'hidden_columns' ,'table_header' ,'total')
        );
    }
}