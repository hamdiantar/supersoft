<?php
namespace App\AccountingModule\ExportCore;

class ExportFactory {
    const EXPORTABLE = [
        'print' => '\App\AccountingModule\ExportCore\Printer',
        'excel' => '\App\AccountingModule\ExportCore\ExportExcel',
        'GL-print' => '\App\AccountingModule\ExportCore\GL_Printer',
        'GL-excel' => '\App\AccountingModule\ExportCore\GL_ExportExcel',
        'TP-print' => '\App\AccountingModule\ExportCore\TrialBalancePrinter',
        'TP-excel' => '\App\AccountingModule\ExportCore\TrialBalanceExportExcel',
        'BS-print' => '\App\AccountingModule\ExportCore\BalanceSheetPrinter',
        'BS-excel' => '\App\AccountingModule\ExportCore\BalanceSheetExportExcel',
    ];

    static function build_exportable($exportable ,$collection ,$columns ,$hidden_columns) : AbstractExportable {
        $class_name = self::EXPORTABLE[$exportable];
        return new $class_name($collection ,$columns ,$hidden_columns);
    }
}