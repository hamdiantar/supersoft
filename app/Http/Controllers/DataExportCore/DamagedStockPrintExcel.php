<?php


namespace App\Http\Controllers\DataExportCore;

use Maatwebsite\Excel\Facades\Excel;

/**
 * Class DamagedStockPrintExcel
 * @package App\Http\Controllers\DataExportCore
 * @author Eng-Hamdi Antar <hamdiantar27@gmail.com>
 */
class DamagedStockPrintExcel
{
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
            'date' => 'date',
            'number' => 'number',
            'total' => 'total',
            'status' => 'status',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ];
        $this->columns = [
            'id' ,'branch', 'date', 'number', 'total', 'status', 'created_at', 'updated_at'
        ];
        $this->header = [
            'id' => __('#'),
            'branch' => __('Branch'),
            'date' => __('Date'),
            'number' => __('opening-balance.serial-number'),
            'total' => __('Total'),
            'status' => __('Concession Status'),
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
            'title' => __('Damaged Stock'),
            'custom_echo' => function ($columns_keys ,$record, $index) {
                echo '<tr>';
                foreach ($columns_keys as $key) {
                    switch ($key) {
                        case 'id':
                            $value = $index + 1;
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
        return Excel::download($export_object, 'Damaged-Stock'.date('YmdHis').'.csv');
    }
}
