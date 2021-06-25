<?php
namespace App\Http\Controllers\DataExportCore\MoneyPermissions;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\DataExportCore\ServerSideExport;

class BankExchanges {
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
            'bank_from' => 'bank_from',
            'bank_to' => 'bank_to',
            'destination_type' => 'destination_type',
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
            'locker_from' => __('words.from'),
            'locker_to' => __('words.to'),
            'destination_type' => __('words.destination-type'),
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
            'locker-from' => __('words.from'),
            'locker-to' => __('words.to'),
            'destination-type' => __('words.destination-type'),
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
            'title' => __('words.bank-exchanges'),
            'custom_echo' => function ($columns_keys ,$record) {
                echo '<tr>';
                    foreach ($columns_keys as $key) {
                        switch ($key) {
                            case 'bank_from':
                                $value = optional($record->fromBank)->name;
                                break;
                            case 'bank_to':
                                $value = $record->destination_type == 'locker' ? optional($record->toLocker)->name : optional($record->toBank)->name;
                                break;
                            case 'destination_type':
                                $value = __('words.'.$record->destination_type);
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
                    case 'bank_from':
                        $value = optional($record->fromBank)->name;
                        break;
                    case 'bank_to':
                        $value = $record->destination_type == 'locker' ? optional($record->toLocker)->name : optional($record->toBank)->name;
                        break;
                    case 'destination_type':
                        $value = __('words.'.$record->destination_type);
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
        return Excel::download($export_object, 'Bank-Exchanges-'.date('YmdHis').'.csv');
    }
}