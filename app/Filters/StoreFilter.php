<?php

namespace App\Filters;

use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StoreFilter
{
    public function filter(Request $request): Builder
    {
        return Store::where(function ($query) use ($request) {
            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->has('name') && $request->name != '' && $request->name != null) {
                $query->where('name_ar', 'like', '%' . $request->name . '%')
                    ->orWhere('name_en', 'like', '%' . $request->name . '%');
            }

            if ($request->has('employee') && $request->employee != '' && $request->employee != null) {
                $employeeId = $request->employee;
                $query->whereHas('storeEmployeeHistories', function ($q) use ($employeeId) {
                    $q->where('employee_id', $employeeId);
                });
            }
        });
    }
}
