<?php


namespace App\Filters;


use App\Models\EmployeeDelay;
use App\Models\EmployeeSalary;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EmployeeSalaryFilter
{
    public function filter(Request $request): Builder
    {
        return EmployeeSalary::where(function ($query) use ($request) {

            if ($request->has('employee_data_id') && $request->employee_data_id != '' && $request->employee_data_id != null) {
                $query->where('employee_id',  $request->employee_data_id);
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
