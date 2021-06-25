<?php

namespace App\Filters;

use App\Models\ServicePackage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ServicePackageFilter
{
    public function filter(Request $request): Builder
    {
        return ServicePackage::where(function ($query) use ($request) {
            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->has('name') && $request->name != '' && $request->name != null) {
                $query->where('name_ar', 'like', '%' . $request->name . '%');
            }
        });
    }
}
