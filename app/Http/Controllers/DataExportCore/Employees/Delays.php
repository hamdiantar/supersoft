<?php
namespace App\Http\Controllers\DataExportCore\Employees;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\DataExportCore\ServerSideExport;

class Delays {
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
            'status' => 'status',
            'hours' => 'hours',
            'minutes' => 'minutes',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ];
        $this->columns = [
            'name' ,'status', 'hours', 'minutes', 'created_at', 'updated_at'
        ];
        $this->header = [
            'name' => __('Employee Name'),
            'status' => __('Status'),
            'hours' => __('Number Of Hours'),
            'minutes' => __('Number Of Minutes'),
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
            'status' => __('Status'),
            'hours' => __('Number Of Hours'),
            'minutes' => __('Number Of Minutes'),
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
            'title' => __('Employees Delays Printing'),
            'custom_echo' => function ($columns_keys ,$record) {
                echo '<tr>';
                    foreach ($columns_keys as $key) {
                        switch ($key) {
                            case 'name':
                                $value = optional($record->employeeDate)->name;
                                break;
                            case 'status':
                                $value = $record->type === __('Delay') ? __('Delay') : __('Extra');
                                break;
                            case 'hours':
                                $value = $record->number_of_hours;
                                break;
                            case 'minutes':
                                $value = $record->number_of_minutes;
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
                    case 'name':
                        $value = optional($record->employeeDate)->name;
                        break;
                    case 'status':
                        $value = $record->type === __('Delay') ? __('Delay') : __('Extra');
                        break;
                    case 'hours':
                        $value = $record->number_of_hours;
                        break;
                    case 'minutes':
                        $value = $record->number_of_minutes;
                        break;
                    default:
                        $value = $record->$key;
                        break;
                }
                $data[] = $value;
            }
            return $data;
        });
        return Excel::download($export_object, 'Employees-Delays-'.date('YmdHis').'.csv');
    }
}