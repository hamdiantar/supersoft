<?php
namespace App\AccountingModule\ExportCore\IncomeList;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet implements FromQuery, WithHeadings, WithLimit, WithChunkReading, WithMapping, WithTitle {
    protected $collection ,$title;

    function __construct($collection ,$title) {
        $this->collection = $collection;
        $this->title = $title;
    }

    public function chunkSize(): int {
        return 500;
    }

    public function limit(): int {
        return 30000;
    }

    public function headings(): array {
        return [
            __('accounting-module.account-code'),
            __('accounting-module.account-name'),
            __('accounting-module.debit-balance'),
            __('accounting-module.credit-balance')
        ];
    }

    public function query() {
        return $this->collection;
    }

    public function map($row): array {
        return [
            $row->account_code,
            $row->account_name,
            $row->debit_balance,
            $row->credit_balance
        ];
    }

    public function title(): string {
        return $this->title;
    }
}
