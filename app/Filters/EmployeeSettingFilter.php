<?php

namespace App\Filters;

use App\Models\EmployeeSetting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EmployeeSettingFilter
{
    public function filter(Request $request): Builder
    {
        return EmployeeSetting::where(function ($query) use ($request) {
            if ($request->has('name') && $request->name != '' && $request->name != null) {
                $query->where('name_ar', 'like', '%' . $request->name . '%')
                    ->orWhere('name_en', 'like', '%' . $request->name . '%');
            }

            if ($request->has('type_account') && $request->type_account != '' && $request->type_account != null) {
                $query->where('type_account', $request->type_account);
            }

            if ($request->has('status') && $request->status != '' && $request->status != null) {
                $query->where('status', $request->status);
            }

            if ($request->has('shift_id') && $request->shift_id != '' && $request->shift_id != null) {
                $query->where('shift_id', $request->shift_id);
            }
            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }
        });
    }
}
