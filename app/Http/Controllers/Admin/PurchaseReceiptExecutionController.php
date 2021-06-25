<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PurchaseReceiptExecution\CreateRequest;
use App\Models\PurchaseReceipt;
use App\Models\PurchaseReceiptExecution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseReceiptExecutionController extends Controller
{
    public function save(CreateRequest $request)
    {
        try {

            $purchaseReceipt = PurchaseReceipt::find($request['item_id']);

            $data = $request->validated();

            $execution = $purchaseReceipt->execution;

            if ($execution) {

                $execution->update($data);

            }else {

                $data['purchase_receipt_id'] = $request['item_id'];
                PurchaseReceiptExecution::create($data);
            }

            return redirect()->back()->with(['message'=> __('Execution time saved successfully'), 'alert-type'=>'success']);

        } catch (\Exception $e) {
            return redirect()->back()->with(['message'=> __('sorry, please try later'), 'alert-type'=>'error']);
        }
    }
}
