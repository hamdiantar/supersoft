<?php
namespace App\Http\Controllers\DataExportCore;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class StoresTransfers {
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
            'branch' => 'branch',
            'transfer_date' => 'transfer_date',
            'transfer_number' => 'transfer_number',
            'store_from' => 'store_from',
            'store_to' => 'store_to',
            'total' => 'total',
            'status' => 'status',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];
        $this->columns = [
            'id', 'branch', 'transfer_date','transfer_number' , 'store_from', 'store_to','total', 'status', 'created_at', 'updated_at'
        ];
        $this->header = [
            'id' => __('#'),
            'branch' => __('Branch'),
            'transfer_date' => __('words.transfer-date'),
            'transfer_number' => __('words.transfer-number'),
            'store_from' => __('words.store-from'),
            'store_to' => __('words.store-to'),
            'total' => __('words.total'),
            'status' =>  __('Concession Status'),
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

            'branch' =>__('Branch'),
            'transfer-date' => __('words.transfer-date'),
            'transfer-number' => __('opening-balance.serial-number'),
            'store-from' => __('words.store-from'),
            'store-to' => __('words.store-to'),
            'total' => __('words.total'),
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
            'title' => __('words.stores-transfers'),
            'custom_echo' => function ($columns_keys ,$record, $index) {
                echo '<tr>';
                    foreach ($columns_keys as $key) {
                        switch ($key) {
                            case 'id':
                                $value = $index + 1;
                                break;
                            case 'store_from':
                                $value = optional($record->store_from)->name;
                                break;
                            case 'store_to':
                                $value = optional($record->store_to)->name;
                                break;
                            case 'status':
                                $value = $record->concession ? $record->concession->status : '---';
                                break;
                            case 'branch':
                                $value =  optional($record->branch)->name;
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
                    case 'id':
                        $value = $record->id;
                        break;
                    case 'store_from':
                        $value = optional($record->store_from)->name;
                        break;
                    case 'store_to':
                        $value = optional($record->store_to)->name;
                        break;
                    case 'status':
                        $value = $record->concession ? $record->concession->status : '---';
                        break;
                    case 'branch':
                        $value =  optional($record->branch)->name;
                        break;
                    default:
                        $value = $record[$key];
                        break;
                }
                $data[] = $value;
            }
            return $data;
        });
        return Excel::download($export_object, 'stores-transfers-'.date('YmdHis').'.csv');
    }
}
