<?php
namespace App\AccountingModule\ExportCore\IncomeList;

use Excel as Maatwebsite;

class Excel {
    protected $expense_collection;
    protected $revenue_collection;

    function __construct($expense_collection ,$revenue_collection) {
        $this->expense_collection = $expense_collection;
        $this->revenue_collection = $revenue_collection;
    }

    function invoke_export() {
        $export_object = new ExportSheets($this->expense_collection ,$this->revenue_collection);
        return Maatwebsite::download($export_object, 'income-list-'.date('YmdHis').'.xlsx');
    }
}