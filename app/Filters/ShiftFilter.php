<?php

namespace App\Filters;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ShiftFilter
{
    public function filter(Request $request): Builder
    {
        return Shift::where(function ($query) use ($request) {
            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->has('name') && $request->name != '' && $request->name != null) {
                $query->where('name_ar', 'like', '%' . $request->name . '%');
            }

            if ($request->has('Saturday') && $request->Saturday == 1) {
                $query->where('Saturday', $request->Saturday);
            }

            if ($request->has('sunday') && $request->sunday == 1) {
                $query->where('sunday', $request->sunday);
            }

            if ($request->has('monday') && $request->monday == 1) {
                $query->where('monday', $request->monday);
            }

            if ($request->has('tuesday') && $request->tuesday == 1) {
                $query->where('tuesday', $request->tuesday);
            }

            if ($request->has('wednesday') && $request->wednesday == 1) {
                $query->where('wednesday', $request->wednesday);
            }

            if ($request->has('thursday') && $request->thursday == 1) {
                $query->where('thursday', $request->thursday);
            }

            if ($request->has('friday') && $request->friday == 1) {
                $query->where('friday', $request->friday);
            }
        });
    }
}
