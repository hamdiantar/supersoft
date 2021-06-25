<?php

namespace App\Filters;

use App\Models\ExpensesType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ExpenseTypeFilter
{
    public function filter(Request $request): Builder
    {
        return ExpensesType::where(function ($query) use ($request) {
            if ($request->has('type') && $request->type != '' && $request->type != null) {
                $query->where('type_ar', 'like', '%' . $request->type . '%');
            }
            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }
        });
    }
}
