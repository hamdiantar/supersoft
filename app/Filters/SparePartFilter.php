<?php

namespace App\Filters;

use App\Models\SparePart;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SparePartFilter
{
    public function filter(Request $request): Builder
    {
        return SparePart::where(function ($query) use ($request) {

            if ( request()->segment(3) == 'sub-parts-types'){

                $query->where('spare_part_id', '!=', null);

            }else{

                $query->where('spare_part_id', null);
            }

            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->has('main_type') && $request->main_type != '' && $request->main_type != null) {
                $query->where('spare_part_id', $request->main_type);
            }

            if ($request->has('type') && $request->type != '' && $request->type != null) {
                $query->where('type_ar', 'like', '%' . $request->type . '%')
                    ->orWhere('type_en', 'like', '%' . $request->type . '%');
            }
//
//            if ($request->has('status') && $request->status != '' && $request->status != null) {
//                $query->where('status', $request->status);
//            }
        });
    }
}
