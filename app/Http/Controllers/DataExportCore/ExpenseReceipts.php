<?php
namespace App\Http\Controllers\DataExportCore;

use Maatwebsite\Excel\Facades\Excel;

class ExpenseReceipts {
    protected $view_path;
    protected $query_builder;
    protected $columns_definitions;
    protected $hidden_columns;
    protected $columns;
    protected $header;

    function __construct($query_builder ,$visible_columns) {
        $this->view_path = '';
        $this->columns_definitions = [
            'expense_no' => 'id',
            'receiver' => 'receiver',
            'expense_item' => 'expense_item_id',
            'cost' => 'cost',
            'deportation_method' => 'deportation_method',
            'deportation' => 'deportation',
            'payment_type' => 'payment_type',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ];
        $this->columns = [
            'id' ,'receiver', 'expense_item_id', 'cost', 'deportation_method', 'deportation', 'payment_type', 'created_at', 'updated_at'
        ];
        $this->header = [
            'id' => __('Expense No'),
            'receiver' => __('Receiver'),
            'expense_item_id' => __('Expense Item'),
            'cost' => __('Cost'),
            'deportation_method' => __('Deportation Method'),
            'deportation' => __('Deportation'),
            'payment_type' => __('Payment Type'),
            'created_at' => __('Created At'),
            'updated_at' => __('Updated At')
        ];
        $this->query_builder = $query_builder;
        $visible_columns = explode(',' ,$visible_columns);
        foreach($visible_columns as $col) {
            if (!$col) continue;
            $this->hidden_columns[] = $this->columns_definitions[implode("_" ,explode("-" ,$col))];
        }
    }

    function __invoke($action_for) {
        $action_for = in_array($action_for ,['print' ,'excel']) ? $action_for : 'print';
        return $this->$action_for();
    }

    private function print() {
        $columns_keys = [];
        if ($this->hidden_columns)
            foreach($this->columns as $col) {
                if (!in_array($col ,$this->hidden_columns)) $columns_keys[] = $col;
            }
        else $columns_keys = $this->columns;
        return view('server-side-print' ,[
            'collection' => $this->query_builder,
            'columns_keys' => $columns_keys,
            'header' => $this->header,
            'title' => __('Expenses Receipts Printing'),
            'custom_echo' => function ($columns_keys ,$record) {
                echo '<tr>';
                    foreach ($columns_keys as $key) {
                        switch ($key) {
                            case 'id':
                                $value = '##_'.$record->id;
                                break;
                            case 'expense_item_id':
                                $value = optional($record->expenseItem)->item;
                                break;
                            case 'deportation':
                                $value = __("words.".$record->deportation);
                                break;
                            case 'payment_type':
                                $value = __("words.".$record->payment_type);
                                break;
                            case 'deportation_method':
                                if ($record->deportation == 'safe' && $record->locker) $value = $record->locker->name;
                                else if ($record->deportation == 'bank' && $record->bank) $value = $record->bank->name;
                                else $value = '';
                                break;
                            default:
                                $value = $record[$key];
                                break;
                        }
                        echo "<td class='text-center'> $value </td>";
                    }
                echo '</tr>';
            }
        ]);
    }

    private function excel() {
        $columns_keys = [];
        if ($this->hidden_columns)
            foreach($this->columns as $col) {
                if (!in_array($col ,$this->hidden_columns)) $columns_keys[] = $col;
            }
        else $columns_keys = $this->columns;
        ob_end_clean();
        ob_start();
        $export_object = new ServerSideExport($this->query_builder ,$columns_keys ,$this->header);
        $export_object->set_custom_map(function ($columns ,$record) {
            $data = [];
            foreach ($columns as $key) {
                switch ($key) {
                    case 'id':
                        $value = '##_'.$record->id;
                        break;
                    case 'expense_item_id':
                        $value = optional($record->expenseItem)->item;
                        break;
                    case 'payment_type':
                        $value = __("words.".$record->payment_type);
                        break;
                    case 'deportation':
                        $value = __("words.".$record->deportation);
                        break;
                    case 'deportation_method':
                        if ($record->deportation == 'safe' && $record->locker) $value = $record->locker->name;
                        else if ($record->deportation == 'bank' && $record->bank) $value = $record->bank->name;
                        else $value = '';
                        break;
                    default:
                        $value = $record->$key;
                        break;
                }
                $data[] = $value;
            }
            return $data;
        });
        return Excel::download($export_object, 'Expenses-Receipts-'.date('YmdHis').'.csv');
    }
}