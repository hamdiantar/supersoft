<?php
namespace App\Http\Controllers\DataExportCore\Employees;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\DataExportCore\ServerSideExport;

class Data {
    protected $view_path;
    protected $query_builder;
    protected $columns_definitions;
    protected $hidden_columns;
    protected $columns;
    protected $header;

    function __construct($query_builder ,$visible_columns) {
        $this->view_path = '';
        $this->columns_definitions = [
            'name' => 'name',
            'setting' => 'setting',
            'phone' => 'phone',
            'status' => 'status',
            'debit' => 'debit',
            'credit' => 'credit',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ];
        $this->columns = [
            'name' ,'setting', 'phone', 'status', 'debit', 'credit', 'created_at', 'updated_at'
        ];
        $this->header = [
            'name' => __('Employee Name'),
            'setting' => __('Employee Category'),
            'phone' => __('Phone'),
            'status' => __('Status'),
            'debit' => __('words.employee-debit'),
            'credit' => __('words.employee-credit'),
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

    static function get_my_view_columns() {
        return [
            'name' => __('Employee Name'),
            'setting' => __('Employee Category'),
            'phone' => __('Phone'),
            'status' => __('Status'),
            'debit' => __('words.employee-debit'),
            'credit' => __('words.employee-credit')
        ];
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
            'title' => __('Employees Printing'),
            'custom_echo' => function ($columns_keys ,$record) {
                $balance = $record->direct_balance();
                echo '<tr>';
                    foreach ($columns_keys as $key) {
                        switch ($key) {
                            case 'setting':
                                $value = optional($record->employeeSetting)->name;
                                break;
                            case 'phone':
                                $value = $record->phone2;
                                break;
                            case 'status':
                                $value = $record->status == 1 ? __('Active') : __('inActive');
                                break;
                            case 'debit':
                                $value = $balance['debit'];
                                break;
                            case 'credit':
                                $value = $balance['credit'];
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
            $balance = $record->direct_balance();
            $data = [];
            foreach ($columns as $key) {
                switch ($key) {
                    case 'setting':
                        $value = optional($record->employeeSetting)->name;
                        break;
                    case 'phone':
                        $value = $record->phone2;
                        break;
                    case 'status':
                        $value = $record->status == 1 ? __('Active') : __('inActive');
                        break;
                    case 'debit':
                        $value = $balance['debit'];
                        break;
                    case 'credit':
                        $value = $balance['credit'];
                        break;
                    default:
                        $value = $record->$key;
                        break;
                }
                $data[] = $value;
            }
            return $data;
        });
        return Excel::download($export_object, 'Employees-'.date('YmdHis').'.csv');
    }
}