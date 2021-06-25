<?php
namespace App\Http\Controllers\DataExportCore\MoneyPermissions;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\DataExportCore\ServerSideExport;

class BankReceives {
    protected $view_path;
    protected $query_builder;
    protected $columns_definitions;
    protected $hidden_columns;
    protected $columns;
    protected $header;

    function __construct($query_builder ,$visible_columns) {
        $this->view_path = '';
        $this->columns_definitions = [
            'permission_number' => 'permission_number',
            'exchange_number' => 'exchange_number',
            'source_type' => 'source_type',
            'money_receiver' => 'money_receiver',
            'amount' => 'amount',
            'operation_date' => 'operation_date',
            'status' => 'status',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];
        $this->columns = [];
        foreach($this->columns_definitions as $key => $value) {
            $this->columns[] = $key;
        }
        $this->header = [
            'permission_number' => __('words.permission-number'),
            'exchange_number' => __('words.exchange-number'),
            'source_type' => __('words.source-type'),
            'money_receiver' => __('words.money-receiver'),
            'amount' => __('the Amount'),
            'operation_date' => __('words.operation-date'),
            'status' => __('words.permission-status'),
            'created_at' => __('created at'),
            'updated_at' => __('Updated at'),
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

    static function get_my_view_columns() {
        return [
            'permission-number' => __('words.permission-number'),
            'exchange-number' => __('words.exchange-number'),
            'source-type' => __('words.source-type'),
            'money-receiver' => __('words.money-receiver'),
            'amount' => __('the Amount'),
            'operation-date' => __('words.operation-date'),
            'status' => __('words.permission-status'),
        ];
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
            'title' => __('words.bank-receives'),
            'custom_echo' => function ($columns_keys ,$record) {
                echo '<tr>';
                    foreach ($columns_keys as $key) {
                        switch ($key) {
                            case 'exchange_number':
                                $value = $record->source_type == 'bank' ? optional($record->exchange_permission)->permission_number : optional($record->locker_exchange_permission)->permission_number;
                                break;
                            case 'source_type':
                                $value = __('words.'.$record->source_type);
                                break;
                            case 'money_receiver':
                                $value = optional($record->employee)->name;
                                break;
                            case 'status':
                                $value = __('words.'.$record->status);
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
                    case 'exchange_number':
                        $value = $record->source_type == 'bank' ? optional($record->exchange_permission)->permission_number : optional($record->locker_exchange_permission)->permission_number;
                        break;
                    case 'source_type':
                        $value = __('words.'.$record->source_type);
                        break;
                    case 'money_receiver':
                        $value = optional($record->employee)->name;
                        break;
                    case 'status':
                        $value = __('words.'.$record->status);
                        break;
                    default:
                        $value = $record[$key];
                        break;
                }
                $data[] = $value;
            }
            return $data;
        });
        return Excel::download($export_object, 'Bank-Receives-'.date('YmdHis').'.csv');
    }
}