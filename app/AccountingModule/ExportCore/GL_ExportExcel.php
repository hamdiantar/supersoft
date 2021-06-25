<?php
namespace App\AccountingModule\ExportCore;

use Excel;

class GL_ExportExcel extends AbstractExportable {
    protected $show_cost_center = false;

    function set_show_cost($show_cost_center) {
        $this->show_cost_center = $show_cost_center;
    }

    function invoke_export() {
        try {
            $hidden_columns = explode("," ,$this->hidden_columns);
            if ($this->show_cost_center == false) $hidden_columns[] = 'cost_center';
        } catch (\Exception $e) {
            $hidden_columns = NULL;
            if ($this->show_cost_center == false) $hidden_columns[] = 'cost_center';
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
        return Excel::download($export_object, 'general-ledger-'.date('YmdHis').'.xlsx');
    }
}