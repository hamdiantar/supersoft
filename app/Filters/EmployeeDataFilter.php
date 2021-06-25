<?php

namespace App\Filters;

use App\Models\EmployeeData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EmployeeDataFilter
{
    public function filter(Request $request): Builder
    {
        return EmployeeData::where(function ($query) use ($request) {
            if ($request->has('name') && $request->name != '' && $request->name != null) {
                $query->where('name_ar', 'like', '%' . $request->name . '%')
                    ->orWhere('name_en', 'like', '%' . $request->name . '%');
            }

            if ($request->has('phone') && $request->phone != '' && $request->phone != null) {
                $query->where('phone1', 'like', '%' . $request->phone . '%')
                    ->orWhere('phone2', 'like', '%' . $request->phone . '%');
            }

            if ($request->has('employee_setting_id') && $request->employee_setting_id != '' && $request->employee_setting_id != null) {
                $query->where('employee_setting_id', $request->employee_setting_id);
            }

            if ($request->has('id_number') && $request->id_number != '' && $request->id_number != null) {
                $query->where('id_number', $request->id_number);
            }

            if ($request->has('status') && $request->status != '' && $request->status != null) {
                $query->where('status', $request->status);
            }

            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }
        });
    }
}
