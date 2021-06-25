<?php

namespace App\Filters;

use App\Models\RevenueType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RevenueTypeFilter
{
    public function filter(Request $request): Builder
    {
        return RevenueType::where(function ($query) use ($request) {
            if ($request->has('type') && $request->type != '' && $request->type != null) {
                $query->where('type_ar', 'like', '%' . $request->type . '%');
            }
            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }
        });
    }
}
