<?php
namespace App\AccountingModule\ExportCore;

class GL_Printer extends AbstractExportable {
    const view_path = 'accounting-module.general-ledger.printer';

    protected $totals;
    protected $show_cost_center = false;

    function set_show_cost($show_cost_center) {
        $this->show_cost_center = $show_cost_center;
    }

    function set_totals($totals) {
        $this->totals = $totals;
    }

    function invoke_export() {
        $collection = $this->collection;
        $title = __('accounting-module.general-ledger-title');
        $layout_path = $this->common_printer_layout;
        $table_header = $this->columns;
        try {
            $hidden_columns = explode("," ,$this->hidden_columns);
            if ($this->show_cost_center == false) $hidden_columns[] = 'cost-center';
        } catch (\Exception $e) {
            $hidden_columns = NULL;
            if ($this->show_cost_center == false) $hidden_columns[] = 'cost-center';
        }
        $total = $this->totals;

        return view(self::view_path . '.general-ledger' ,
            compact('title' ,'layout_path' ,'collection' ,'hidden_columns' ,'table_header' ,'total')
        );
    }
}