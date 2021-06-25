<?php
namespace App\Http\Controllers\DataExportCore;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMapping;

class ServerSideExport implements FromCollection, WithHeadings, WithLimit, WithChunkReading, WithMapping {
    protected $collection;
    protected $columns;
    protected $header;
    protected $custom_mapping;

    function __construct($collection ,$columns ,$header) {
        $this->collection = $collection;
        $this->columns = $columns;
        $this->header = $header;
    }

    function set_custom_map($fnc) {
        $this->custom_mapping = $fnc;
    }

    public function chunkSize(): int {
        return 500;
    }

    public function limit(): int {
        return 30000;
    }

    public function headings(): array {
        $header = [];
        foreach($this->columns as $col) {
            array_push($header ,$this->header[$col]);
        }
        return $header;
    }

    public function collection() {
        return
            $this
                ->collection
                ->limit($this->limit())
                ->get();
    }

    public function map($row): array {
        if ($this->custom_mapping) {
            $mapping = $this->custom_mapping;
            return $mapping($this->columns ,$row);
        } else {
            $data = [];
            foreach ($this->columns as $col) {
                $data[] = $row->$col;
            }
            return $data;
        }
    }
}
