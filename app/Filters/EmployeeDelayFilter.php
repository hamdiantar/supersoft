<?php

namespace App\Filters;

use App\Models\EmployeeDelay;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EmployeeDelayFilter
{
    public function filter(Request $request): Builder
    {
        return EmployeeDelay::where(function ($query) use ($request) {

            if ($request->has('employee_data_id') && $request->employee_data_id != '' && $request->employee_data_id != null) {
                $query->where('employee_data_id',  $request->employee_data_id);
            }

            if ($request->has('type') && $request->type != '' && $request->type != null) {
                $query->where('type', $request->type);
            }

            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->has('dateFrom') && $request->has('dateTo')
                && $request->dateFrom != '' && $request->dateTo != ''
                && $request->dateFrom != null && $request->dateTo != null) {

                $query->whereBetween('date', [$request->dateFrom, $request->dateTo]);
            }
        });
    }
}
