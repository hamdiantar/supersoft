<?php
namespace App\Http\Controllers\DataExportCore;

use Maatwebsite\Excel\Facades\Excel;

class AccountsTransfers {
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
            'account_from' => 'account_from_id',
            'account_to' => 'account_to_id',
            'the_cost' => 'amount',
            'created_by' => 'created_by',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ];
        $this->columns = [
            'id' ,'account_from_id', 'account_to_id', 'amount', 'created_by', 'created_at', 'updated_at'
        ];
        $this->header = [
            'id' => __('#'),
            'account_from_id' => __('Account From'),
            'account_to_id' => __('Account To'),
            'amount' => __('The Cost'),
            'created_by' => __('Created By'),
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
            'title' => __('Accounts Transfers Printing'),
            'custom_echo' => function ($columns_keys ,$record) {
                echo '<tr>';
                    foreach ($columns_keys as $key) {
                        $value = $record[$key];
                        if ($key == 'account_from_id') $value = optional($record->accountFrom)->name;
                        else if ($key == 'account_to_id') $value = optional($record->accountTo)->name;
                        else if ($key == 'created_by') $value = optional($record->createdBy)->name;
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
            $data = [];
            foreach ($columns as $col) {
                $value = $row->$col;
                if ($col == 'account_from_id') $value = optional($row->accountFrom)->name;
                else if ($col == 'account_to_id') $value = optional($row->accountTo)->name;
                else if ($col == 'created_by') $value = optional($row->createdBy)->name;
                $data[] = $value;
            }
            return $data;
        });
        return Excel::download($export_object, 'Accounts-Transfers-'.date('YmdHis').'.csv');
    }
}