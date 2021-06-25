<?php

namespace App\Http\ViewComposers;

use App\Models\Branch;
use Illuminate\Contracts\View\View;

class PrintDataComposer
{
    public function compose(View $view)
    {
        if (auth()->guard('customer')->check()) {
            $branchToPrint = \App\Models\Branch::find(auth()->guard('customer')->user()->branch_id);
            $view->with('branchToPrint', $branchToPrint);
        } else {
            $view->with('branchToPrint', Branch::where('id', optional(auth()->user())->branch_id)->first());
        }
    }
}
