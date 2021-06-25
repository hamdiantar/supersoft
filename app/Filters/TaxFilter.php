<?php

namespace App\Filters;

use App\Models\TaxesFees;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use function Matrix\trace;

class TaxFilter
{
    public function filter(Request $request): Builder
    {
        return TaxesFees::where(function ($query) use ($request) {
            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->has('name') && $request->name != '' && $request->name != null) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }
            if ($request->has('id') && $request->id != '' && $request->id != null) {
                $query->where('id',$request->id);
            }

            if ($request->has('on_parts')) {
                $query->where('on_parts', true);
            }
        });
    }
}
