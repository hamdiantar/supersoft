<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PurchaseRequestExecution\CreateRequest;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestExecution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseRequestExecutionController extends Controller
{
    public function save(CreateRequest $request)
    {
        try {

            $purchaseRequest = PurchaseRequest::find($request['item_id']);

            $data = $request->validated();

            $execution = $purchaseRequest->execution;

            if ($execution) {

                $execution->update($data);

            }else {

                $data['purchase_request_id'] = $request['item_id'];
                PurchaseRequestExecution::create($data);
            }

            return redirect()->back()->with(['message'=> __('Execution time saved successfully'), 'alert-type'=>'success']);

        } catch (\Exception $e) {
            return redirect()->back()->with(['message'=> __('sorry, please try later'), 'alert-type'=>'error']);
        }
    }
}
