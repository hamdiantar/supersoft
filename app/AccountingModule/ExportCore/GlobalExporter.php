<?php
namespace App\AccountingModule\ExportCore;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMapping;

class GlobalExporter implements FromCollection, WithHeadings, WithLimit, WithChunkReading, WithMapping {
    protected $collection;
    protected $columns;

    function __construct($collection ,$columns) {
        $this->collection = $collection;
        $this->columns = $columns;
    }

    public function chunkSize(): int {
        return 500;
    }

    public function limit(): int {
        return 30000;
    }

    public function headings(): array {
        $header = [];
        foreach($this->columns as $head) {
            $header[] = __('accounting-module.'. implode("-" ,explode("_" ,$head)));
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
        $data = [];
        foreach($this->columns as $col) {
            $value = $row->$col;
            if ($col == 'transactions_debit')
                $value = abs($row->debit_balance);
            else if (($col == 'transactions_credit'))
                $value = abs($row->credit_balance);
            $data[] = $value;
        }
        return $data;
    }
}
