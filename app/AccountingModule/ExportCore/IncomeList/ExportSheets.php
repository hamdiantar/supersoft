<?php
namespace App\AccountingModule\ExportCore\IncomeList;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportSheets implements WithMultipleSheets {
    use Exportable;

    protected $expenses ,$revenues;

    function __construct($expenses ,$revenues) {
        $this->expenses = $expenses;
        $this->revenues = $revenues;
    }

    public function sheets(): array {
        return [
            new Sheet($this->expenses ,'Expense Accounts'),
            new Sheet($this->revenues ,'Revenue Accounts')
        ];
    }
}
