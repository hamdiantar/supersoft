<?php
namespace App\AccountingModule\ExportCore;

abstract class AbstractExportable {
    protected $collection ,$columns ,$hidden_columns ,$common_printer_layout;

    function __construct($collection ,$columns ,$hidden_columns) {
        $this->collection = $collection;
        $this->columns = $columns;
        $this->hidden_columns = $hidden_columns;
        $this->common_printer_layout = 'accounting-module.common-printer-layout';
    }

    abstract function invoke_export();
}