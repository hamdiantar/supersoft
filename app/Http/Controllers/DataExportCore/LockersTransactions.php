<?php
namespace App\Http\Controllers\DataExportCore;

use Maatwebsite\Excel\Facades\Excel;

class LockersTransactions {
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
            'locker_name' => 'locker_id',
            'account_name' => 'account_id',
            'operation_type' => 'type',
            'the_cost' => 'amount',
            'created_by' => 'created_by',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ];
        $this->columns = [
            'id' ,'locker_id', 'account_id', 'type', 'amount', 'created_by', 'created_at', 'updated_at'
        ];
        $this->header = [
            'id' => __('#'),
            'locker_id' => __('Locker name'),
            'account_id' => __('Account name'),
            'type' => __('Operation Type'),
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
            'title' => __('Lockers Transactions Printing'),
            'custom_echo' => function ($columns_keys ,$record) {
                echo '<tr>';
                    foreach ($columns_keys as $key) {
                        $value = $record[$key];
                        if ($key == 'locker_id') $value = optional($record->locker)->name;
                        else if ($key == 'account_id') $value = optional($record->account)->name;
                        else if ($key == 'type') $value = $value == 'deposit' ? __('Deposit') : __('Withdrawal');
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
                if ($col == 'locker_id') $value = optional($row->locker)->name;
                else if ($col == 'account_id') $value = optional($row->account)->name;
                else if ($col == 'type') $value = $value == 'deposit' ? __('Deposit') : __('Withdrawal');
                else if ($col == 'created_by') $value = optional($row->createdBy)->name;
                $data[] = $value;
            }
            return $data;
        });
        return Excel::download($export_object, 'Lockers-Transactions-'.date('YmdHis').'.csv');
    }
}