<?php
namespace App\Http\Controllers\DataExportCore;

class TradingAccountExport {
    protected $collections;

    function __construct($collections) {
        $this->collections = $collections;
    }

    function __invoke($action_for) {
        $action_for = in_array($action_for ,['print' ,'excel']) ? $action_for : 'print';
        return $this->$action_for();
    }

    private function print() {
        return view('accounting-module.trading-accounts.print' ,[
            'collections' => $this->collections,
            'title' => __('accounting-module.trading-account-index')
        ]);
    }

    private function excel() {
        $fileName = 'trading-accounts-report-'.date('YmdHis').'.csv';
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Pragma: no-cache");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Expires: 0");

        ob_end_clean();
        ob_start();

        $columns = [
            __('accounting-module.debit') .' '. __('accounting-module.account-name'),
            __('accounting-module.debit') .' '. __('accounting-module.amount'),
            __('accounting-module.credit') .' '. __('accounting-module.account-name'),
            __('accounting-module.credit') .' '. __('accounting-module.amount')
        ];
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        $collections = $this->collections;
        
        $big_count = count($collections['debit_collection']) > count($collections['credit_collection']);
        if ($big_count) {
            foreach($collections['debit_collection'] as $index => $debit_acc) {
                $credit_acc = isset($collections['credit_collection'][$index]) ? $collections['credit_collection'][$index] : NULL;
                $row = [
                    $debit_acc->account_name .' '. $debit_acc->account_code,
                    $debit_acc->debit_balance,
                    $credit_acc ? ($credit_acc->account_name .' '. $credit_acc->account_code) : '',
                    $credit_acc ? $credit_acc->credit_balance : ''
                ];
                fputcsv($file, $row);
            }
        } else {
            foreach($collections['credit_collection'] as $index => $credit_acc) {
                $debit_acc = isset($collections['debit_collection'][$index]) ? $collections['debit_collection'][$index] : NULL;
                $row = [ $debit_acc ? ($debit_acc->account_name .' '. $debit_acc->account_code) : '',
                    $debit_acc ? $debit_acc->debit_balance : '',
                    $credit_acc ? ($credit_acc->account_name .' '. $credit_acc->account_code) : '',
                    $credit_acc ? $credit_acc->credit_balance : ''
                ];
                fputcsv($file, $row);
            }
        }

        fclose($file);
        return ob_get_clean();
    }
}