<?php

namespace App\Http\Controllers\DataExportCore;

use Maatwebsite\Excel\Facades\Excel;

class SpareParts
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
            'type' => 'spare_part_type_id',
            'quantity' => 'quantity',
            'status' => 'status',
            'reviewable' => 'reviewable',
            'taxable' => 'taxable',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ];

        $this->columns = [
            'id',
            'name',
            'quantity',
            'status',
            'reviewable',
            'taxable',
            'created_at',
            'updated_at'
        ];

        $this->header = [
            'id' => __('#'),
            'name' => __('Name'),
            'quantity' => __('Quantity'),
            'status' => __('Status'),
            'reviewable' => __('Reviewable'),
            'taxable' => __('Taxable'),
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
            'title' => __('Spares Parts Printing'),
            'custom_echo' => function ($columns_keys, $record, $index) {
                echo '<tr>';
                foreach ($columns_keys as $key) {
                    $value = $record[$key];
                    if ($key == 'spare_part_type_id') {
                        $value = optional($record->sparePartsType)->type;
                    } else {
                        if ($key == 'status') {
                            $value = $value == 1 ? __('Active') : __('inActive');
                        }
                        if ($key == 'reviewable') {
                            $value = $value == 1 ? __('Reviewed') : __('Not reviewed');
                        }
                        if ($key == 'taxable') {
                            $value = $value == 1 ? __('Active') : __('inActive');
                        }
                        if ($key == 'id') {
                            $value = $index + 1;
                        }
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
                if ($col == 'spare_part_type_id') {
                    $value = optional($row->sparePartsType)->type;
                } else {
                    if ($col == 'status') {
                        $value = $value == 1 ? __('Active') : __('inActive');
                    }
                    if ($col == 'reviewable') {
                        $value = $value == 1 ? __('Reviewed') : __('Not reviewed');
                    }
                    if ($col == 'taxable') {
                        $value = $value == 1 ? __('Active') : __('inActive');
                    }
                    if ($col == 'id') {
                        $value = $index + 1;
                    }
                }
                $data[] = $value;
            }
            return $data;
        });
        return Excel::download($export_object, 'Spares-Parts-' . date('YmdHis') . '.csv');
    }
}
