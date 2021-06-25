<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SupplyOrderExecution\CreateRequest;
use App\Models\SupplyOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplyOrderExecutionController extends Controller
{
    public function save(CreateRequest $request)
    {
        try {

            $supplyOrder = SupplyOrder::find($request['item_id']);

            $data = $request->validated();

            $execution = $supplyOrder->execution;

            if ($execution) {

                $execution->update($data);

            }else {

                $data['supply_order_id'] = $request['item_id'];
                \App\Models\SupplyOrderExecution::create($data);
            }

            return redirect()->back()->with(['message'=> __('Execution time saved successfully'), 'alert-type'=>'success']);

        } catch (\Exception $e) {
            return redirect()->back()->with(['message'=> __('sorry, please try later'), 'alert-type'=>'error']);
        }
    }
}
