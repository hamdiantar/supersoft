<?php
namespace App\Http\Controllers\DataExportCore;

use Maatwebsite\Excel\Facades\Excel;

class CustomersCar {
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
            'customer_type' => 'type',
            'customer_category' => 'customer_category_id',
            'status' => 'status',
            'cars_number' => 'cars_number',
            'balance_for' => 'balance_for',
            'balance_to' => 'balance_to',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ];
        $this->columns = [
            'name', 'type', 'customer_category_id', 'status', 'cars_number', 'balance_for', 'balance_to', 'created_at', 'updated_at'
        ];
        $this->header = [
            'name' => __('Name'),
            'type' => __('Customer Type'),
            'customer_category_id' => __('Customers Category'),
            'status' => __('Status'),
            'cars_number' => __('Cars Number'),
            'balance_for' => __('Balance For'),
            'balance_to' => __('Balance To'),
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
            'title' => __('Customers Cars Printing'),
            'custom_echo' => function ($columns_keys ,$record) {
                $customer_balance = $record->direct_balance();
                echo '<tr>';
                    foreach ($columns_keys as $key) {
                        $value = $record[$key];
                        if ($key == 'customer_category_id') $value = optional($record->customerCategory)->name;
                        else if ($key == 'type') $value = $value == 'person' ? __('Person') : __('Company');
                        else if ($key == 'balance_for') $value = $customer_balance['debit'];
                        else if ($key == 'balance_to') $value = $customer_balance['credit'];
                        else if ($key == 'status') $value = $value == 1 ? __('Active') : __('inActive');
                        else if ($key == 'cars_number') $value = count($record->cars);
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
        $export_object->set_custom_map(function ($columns ,$row) {
            $customer_balance = $row->direct_balance();
            $data = [];
            foreach ($columns as $col) {
                $value = $row->$col;
                if ($col == 'customer_category_id') $value = optional($row->customerCategory)->name;
                else if ($col == 'type') $value = $value == 'person' ? __('Person') : __('Company');
                else if ($col == 'balance_for') $value = $customer_balance['debit'];
                else if ($col == 'balance_to') $value = $customer_balance['credit'];
                else if ($col == 'status') $value = $value == 1 ? __('Active') : __('inActive');
                else if ($col == 'cars_number') $value = count($row->cars);
                $data[] = $value;
            }
            return $data;
        });
        return Excel::download($export_object, 'Customers-Cars-'.date('YmdHis').'.csv');
    }
}