<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PurchaseQuotationExecution\CreateRequest;
use App\Models\PurchaseQuotation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseQuotationExecutionController extends Controller
{
    public function save(CreateRequest $request)
    {
        try {

            $PurchaseQuotation = PurchaseQuotation::find($request['item_id']);

            $data = $request->validated();

            $execution = $PurchaseQuotation->execution;

            if ($execution) {

                $execution->update($data);

            }else {

                $data['purchase_quotation_id'] = $request['item_id'];
                \App\Models\PurchaseQuotationExecution::create($data);
            }

            return redirect()->back()->with(['message'=> __('Execution time saved successfully'), 'alert-type'=>'success']);

        } catch (\Exception $e) {
            return redirect()->back()->with(['message'=> __('sorry, please try later'), 'alert-type'=>'error']);
        }
    }
}
