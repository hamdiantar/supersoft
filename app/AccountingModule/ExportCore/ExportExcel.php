<?php
namespace App\AccountingModule\ExportCore;

use Excel;

class ExportExcel extends AbstractExportable {
    function invoke_export() {
        try {
            $hidden_columns = explode("," ,$this->hidden_columns);
        } catch (\Exception $e) {
            $hidden_columns = NULL;
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
        return Excel::download($export_object, 'daily-restriction-'.date('YmdHis').'.xlsx');
    }
}