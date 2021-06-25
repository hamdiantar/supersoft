<?php

namespace App\Repositories;

use App\Models\StoreEmployeeHistory;

class StoreEmployeeHistoryRepository extends Repository
{
    public function model(): string
    {
        return StoreEmployeeHistory::class;
    }
}
