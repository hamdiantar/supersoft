<?php
namespace App\Http\Controllers\DataExportCore\CustomerPanel;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\DataExportCore\ServerSideExport;

class WorkCards {
    protected $view_path;
    protected $query_builder;
    protected $columns_definitions;
    protected $hidden_columns;
    protected $columns;
    protected $header;

    function __construct($query_builder ,$visible_columns) {
        $this->view_path = '';
        $this->columns_definitions = [
            'card_number' => 'card_number',
            'name' => 'name',
            'phone' => 'phone',
            'status' => 'status',
            'receive' => 'receive',
            'delivery' => 'delivery',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ];
        $this->columns = [
            'card_number' ,'name', 'phone', 'status', 'receive', 'delivery', 'created_at', 'updated_at'
        ];
        $this->header = [
            'card_number' => __('#'),
            'name' => __('Name'),
            'phone' => __('Phone'),
            'status' => __('Status'),
            'receive' => __('Receive Status'),
            'delivery' => __('Delivery Status'),
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
            'card-number' => __('#'),
            'name' => __('Name'),
            'phone' => __('Phone'),
            'status' => __('Status'),
            'receive' => __('Receive Status'),
            'delivery' => __('Delivery Status')
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
        return view('customer-server-side-print' ,[
            'collection' => $this->query_builder,
            'columns_keys' => $columns_keys,
            'header' => $this->header,
            'title' => __('My Work Cards Printing'),
            'custom_echo' => function ($columns_keys ,$record) {
                echo '<tr>';
                    foreach ($columns_keys as $key) {
                        switch ($key) {
                            case 'name':
                                $value = optional($record->customer)->name;
                                break;
                            case 'phone':
                                $value = optional($record->customer)->phone1;
                                break;
                            case 'status':
                                $value = __($record->status);
                                break;
                            case 'receive':
                                $value = $record->receive_car_status == 1 ? __('Received') :  __('Not Received');
                                break;
                            case 'delivery':
                                $value = $record->delivery_car_status == 1 ? __('Delivered') :  __('Not Delivered');
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
                        $value = optional($record->customer)->name;
                        break;
                    case 'phone':
                        $value = optional($record->customer)->phone1;
                        break;
                    case 'status':
                        $value = __($record->status);
                        break;
                    case 'receive':
                        $value = $record->receive_car_status == 1 ? __('Received') :  __('Not Received');
                        break;
                    case 'delivery':
                        $value = $record->delivery_car_status == 1 ? __('Delivered') :  __('Not Delivered');
                        break;
                    default:
                        $value = $record->$key;
                        break;
                }
                $data[] = $value;
            }
            return $data;
        });
        return Excel::download($export_object, 'My-Work-Cards-'.date('YmdHis').'.csv');
    }
}