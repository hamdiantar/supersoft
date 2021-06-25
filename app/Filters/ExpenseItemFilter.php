<?php

namespace App\Filters;

use App\Models\ExpensesItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ExpenseItemFilter
{
    public function filter(Request $request): Builder
    {
        return ExpensesItem::where(function ($query) use ($request) {
            if ($request->has('item') && $request->item != '' && $request->item != null) {
                $query->where('item_ar', 'like', '%' . $request->item . '%');
            }

            if ($request->has('expense_id') && $request->expense_id != '' && $request->expense_id != null) {
                $query->where('expense_id', $request->expense_id);
            }
            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }
        });
    }
}
