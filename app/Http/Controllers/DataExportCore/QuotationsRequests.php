<?php
namespace App\Http\Controllers\DataExportCore;

use Maatwebsite\Excel\Facades\Excel;

class QuotationsRequests {
    protected $view_path;
    protected $query_builder;
    protected $columns_definitions;
    protected $hidden_columns;
    protected $columns;
    protected $header;

    function __construct($query_builder ,$visible_columns) {
        $this->view_path = '';
        $this->columns_definitions = [
            'quotation_number' => 'quotation_number',
            'customer_name' => 'customer_name',
            'customer_phone' => 'customer_phone',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ];
        $this->columns = [
            'quotation_number' ,'customer_name', 'customer_phone', 'created_at', 'updated_at'
        ];
        $this->header = [
            'quotation_number' => __('Quotation Number'),
            'customer_name' => __('Customer Name'),
            'customer_phone' => __('Customer Phone'),
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
            'quotation-number' => __('Quotation Number'),
            'customer-name' => __('Customer Name'),
            'customer-phone' => __('Customer Phone')
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
            'title' => __('Quotations Requests Printing'),
            'custom_echo' => function ($columns_keys ,$record) {
                echo '<tr>';
                    foreach ($columns_keys as $key) {
                        switch ($key) {
                            case 'quotation_number':
                                $value = '##_'.$record->quotation_number;
                                break;
                            case 'customer_name':
                                $value = optional($record->customer)->name;
                                break;
                            case 'customer_phone':
                                $value = optional($record->customer)->phone1;
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
                    case 'quotation_number':
                        $value = '##_'.$record->quotation_number;
                        break;
                    case 'customer_name':
                        $value = optional($record->customer)->name;
                        break;
                    case 'customer_phone':
                        $value = optional($record->customer)->phone1;
                        break;
                    default:
                        $value = $record->$key;
                        break;
                }
                $data[] = $value;
            }
            return $data;
        });
        return Excel::download($export_object, 'Quotations-Requests-'.date('YmdHis').'.csv');
    }
}