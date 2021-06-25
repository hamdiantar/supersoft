<?php
namespace App\AccountingModule\ExportCore\IncomeList;

class Printer {
    const view_path = 'accounting-module.income-list.printer';

    protected $expense_totals;
    protected $revenue_totals;
    protected $expense_collection;
    protected $revenue_collection;
    protected $common_printer_layout;

    function __construct($expense_collection ,$revenue_collection ,$expense_totals ,$revenue_totals) {
        $this->expense_collection = $expense_collection;
        $this->revenue_collection = $revenue_collection;
        $this->expense_totals = $expense_totals;
        $this->revenue_totals = $revenue_totals;
        $this->common_printer_layout = 'accounting-module.common-printer-layout';
    }

    function invoke_export() {
        $layout_path = $this->common_printer_layout;
        $title = __('accounting-module.income-list-title');
        return view(self::view_path . '.income-list' ,[
            'expense_collection' => $this->expense_collection,
            'revenue_collection' => $this->revenue_collection,
            'expense_totals' => $this->expense_totals,
            'revenue_totals' => $this->revenue_totals,
            'layout_path' => $layout_path,
            'title' => $title
        ]);
    }
}