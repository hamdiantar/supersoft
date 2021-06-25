<?php
namespace App\Http\Controllers\DataExportCore;

use Maatwebsite\Excel\Facades\Excel;

class Suppliers {
    protected $view_path;
    protected $query_builder;
    protected $columns_definitions;
    protected $hidden_columns;
    protected $columns;
    protected $header;

    function __construct($query_builder ,$visible_columns) {
        $this->view_path = '';
        $this->columns_definitions = [
            'id' => 'id',
            'supplier_name' => 'name',
            'supplier_group' => 'group_id',
            'supplier_type' => 'type',
            'funds_for' => 'funds_for',
            'funds_on' => 'funds_on',
            'status' => 'status',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ];
        $this->columns = [
            'id', 'name', 'group_id', 'type', 'funds_for', 'funds_on', 'status', 'created_at', 'updated_at'
        ];
        $this->header = [
            'id' => '#',
            'name' => __('Supplier Name'),
            'group_id' => __('Supplier Group'),
            'type' => __('Supplier Type'),
            'funds_for' => __('Funds For'),
            'funds_on' => __('Funds On'),
            'status' => __('Status'),
            'created_at' => __('created at'),
            'updated_at' => __('Updated at')
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
            'title' => __('Supplier Printing'),
            'custom_echo' => function ($columns_keys ,$record) {
                $supplier_balance = $record->direct_balance();
                echo '<tr>';
                    foreach ($columns_keys as $key) {
                        $value = $record[$key];
                        if ($key == 'group_id') $value = optional($record->suppliersGroup)->name;
                        else if ($key == 'type') $value = $value == 'person' ? __('Person') : __('Company');
                        else if ($key == 'funds_for') $value = $supplier_balance['debit'];
                        else if ($key == 'funds_on') $value = $supplier_balance['credit'];
                        else if ($key == 'status') $value = $value == 1 ? __('Active') : __('inActive');
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
            $supplier_balance = $row->direct_balance();
            $data = [];
            foreach ($columns as $col) {
                $value = $row->$col;
                if ($col == 'group_id') $value = optional($row->suppliersGroup)->name;
                else if ($col == 'type') $value = $row->type == 'person' ? __('Person') : __('Company');
                else if ($col == 'funds_for') $value = $supplier_balance['debit'];
                else if ($col == 'funds_on') $value = $supplier_balance['credit'];
                else if ($col == 'status') $value = $row->status ? __('Active') : __('inActive');
                $data[] = $value;
            }
            return $data;
        });
        return Excel::download($export_object, 'suppliers-sheet-'.date('YmdHis').'.csv');
    }
}