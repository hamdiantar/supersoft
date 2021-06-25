<?php

namespace App\Http\Controllers\DataExportCore;

use Maatwebsite\Excel\Facades\Excel;

/**
 * Class OpeningBalancePrintExcel
 * @package App\Http\Controllers\DataExportCore
 * @author Eng-Hamdi Antar <hamdiantar27@gmail.com>
 */
class OpeningBalancePrintExcel
{
    protected $view_path;
    protected $query_builder;
    protected $columns_definitions;
    protected $hidden_columns;
    protected $columns;
    protected $header;

    function __construct($query_builder, $visible_columns)
    {
        $this->view_path = '';
        $this->columns_definitions = [
            'id' => 'id',
            'name' => 'name',
            'operation_date' => 'operation_date',
            'serial_number' => 'serial_number',
            'total_money' => 'total_money',
            'status' => 'status',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ];

        $this->columns = [
            'id',
            'name',
            'operation_date',
            'serial_number',
            'total_money',
            'status',
            'created_at',
            'updated_at'
        ];

        $this->header = [
            'id' => __('#'),
            'name' =>  __('opening-balance.branch'),
            'operation_date' => __('opening-balance.operation-date'),
            'serial_number' => __('opening-balance.serial-number'),
            'total_money' =>__('opening-balance.total'),
            'status' => __('opening-balance.status'),
            'created_at' => __('Created At'),
            'updated_at' => __('Updated At')
        ];
        $this->query_builder = $query_builder;
        $visible_columns = explode(',', $visible_columns);
        foreach ($visible_columns as $col) {
            if (!$col) {
                continue;
            }
            $this->hidden_columns[] = $this->columns_definitions[implode("_", explode("-", $col))];
        }
    }

    function __invoke($action_for)
    {
        $action_for = in_array($action_for, ['print', 'excel']) ? $action_for : 'print';
        return $this->$action_for();
    }

    private function print()
    {
        $columns_keys = [];
        if ($this->hidden_columns) {
            foreach ($this->columns as $col) {
                if (!in_array($col, $this->hidden_columns)) {
                    $columns_keys[] = $col;
                }
            }
        } else {
            $columns_keys = $this->columns;
        }
        return view('server-side-print', [
            'collection' => $this->query_builder,
            'columns_keys' => $columns_keys,
            'header' => $this->header,
            'title' => __('Opening Balance Printing'),
            'custom_echo' => function ($columns_keys, $record, $index) {
                echo '<tr>';
                foreach ($columns_keys as $key) {
                    $value = $record[$key];
                    if ($key == 'id') {
                        $value = $index + 1;
                    }
                    if ($key == 'name') {
                        $value =optional($record->branch)->name;
                    }
                    if ($key == 'status') {
                        $value =$record->concession ? $record->concession->status : '---';
                    }
                    echo "<td class='text-center'> $value </td>";
                }
                echo '</tr>';
            }
        ]);
    }

    private function excel()
    {
        $columns_keys = [];
        if ($this->hidden_columns) {
            foreach ($this->columns as $col) {
                if (!in_array($col, $this->hidden_columns)) {
                    $columns_keys[] = $col;
                }
            }
        } else {
            $columns_keys = $this->columns;
        }
        ob_end_clean();
        ob_start();
        $export_object = new ServerSideExport($this->query_builder, $columns_keys, $this->header);
        $export_object->set_custom_map(function ($columns, $row) {
            $data = [];
            foreach ($columns as $index=>$col) {
                $value = $row->$col;
                if ($col == 'id') {
                    $value = $row->id;
                }
                if ($col == 'name') {
                    $value =optional($row->branch)->name;
                }
                if ($col == 'status') {
                    $value =$row->concession ? $row->concession->status : '---';
                }
                $data[] = $value;
            }
            return $data;
        });
        return Excel::download($export_object, 'opening-balance-' . date('YmdHis') . '.csv');
    }
}
