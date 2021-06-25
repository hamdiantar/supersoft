<?php

namespace App\Filters;

use App\Models\RevenueItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RevenueItemFilter
{
    public function filter(Request $request): Builder
    {
        return RevenueItem::where(function ($query) use ($request) {
            if ($request->has('item') && $request->item != '' && $request->item != null) {
                $query->where('item_ar', 'like', '%' . $request->item . '%');
            }
            if ($request->has('revenue_id') && $request->revenue_id != '' && $request->revenue_id != null) {
                $query->where('revenue_id', $request->revenue_id);
            }

             if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                 $query->where('branch_id', $request->branch_id);
             }
        });
    }
}
