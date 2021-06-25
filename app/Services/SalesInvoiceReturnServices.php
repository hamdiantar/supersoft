<?php


namespace App\Services;


use App\Models\Customer;
use App\Models\Part;
use App\Models\PointRule;
use App\Models\SalesInvoiceItems;
use App\Models\TaxesFees;

trait SalesInvoiceReturnServices
{
    public function calculateItemTotal($request, $part_id, $index)
    {
        $sales_invoice_item = SalesInvoiceItems::find($request['sales_invoice_items_id_'.$index]);

        $data = [
            'sales_invoice_item_id' => $request['sales_invoice_items_id_'.$index],
            'purchase_invoice_id' => $sales_invoice_item->purchase_invoice_id,
            'part_id' => $part_id,
            'available_qty' => $request['available_qy_'.$index],
            'return_qty' => $request['return_qty_'.$index],
            'last_selling_price' => $request['last_selling_price_'.$index],
            'selling_price' => $request['selling_price_'.$index],
            'discount_type' => $request['item_discount_type_'.$index],
            'discount' => $request['item_discount_'.$index],
        ];

        $data['sub_total'] = $request['return_qty_'.$index] * $request['selling_price_'.$index];

        $discount_value = $this->discountValue($request['item_discount_type_'.$index],$request['item_discount_'.$index],
            $data['sub_total']);

        $data['total_after_discount'] = round($data['sub_total'] - $discount_value,2);

        $data['total'] = $data['total_after_discount'];

        return $data;
    }

    public function prepareInvoiceData($request, $branch_id)
    {
        $sales_invoice = \App\Models\SalesInvoice::find($request['sales_invoice_id']);

        $data = [
//            'invoice_number'=> '##_0001' ,  //$request['invoice_number'],
            'branch_id'=> $branch_id,
            'created_by'=> auth()->id(),
            'sales_invoice_id'=> $request['sales_invoice_id'],
            'time'=> $request['time'],
            'date'=> $request['date'],
            'number_of_items'=> count($request['return_part_ids']),
//            'type'=> $request['type'],
            'discount_type'=> $request['discount_type'],
            'discount'=> $request['discount'],
            'customer_discount_status'=> 0,
            'customer_discount'=> $sales_invoice->customer_discount,
            'customer_discount_type'=> $sales_invoice->customer_discount_type,
            'points_rule_id'=> null,
            'points_discount'=> 0,
        ];

        $data['sub_total'] = 0;
        $customer_discount = 0;

        foreach($request['return_part_ids'] as $index => $part_id){

            $item_data = $this->calculateItemTotal($request, $part_id, $index);
            $data['sub_total'] += $item_data['total'];
        }

        if($request->has('customer_discount_check')){

            $data['customer_discount_status'] = 1;

            $customer_discount = $this->discountValue($data['customer_discount_type'],  $data['customer_discount']
                , $data['sub_total']);
        }

        if ($sales_invoice->points_rule_id) {

            $pointRule = PointRule::find($sales_invoice->points_rule_id);

            $data['points_discount'] += $pointRule ? $pointRule->amount : 0;

            $data['points_rule_id'] = $sales_invoice->points_rule_id;
        }

        $discount_value = $this->discountValue($request['discount_type'], $request['discount'], $data['sub_total']);

        $total_discount = round($discount_value + $customer_discount + $data['points_discount'], 2);

        $data['total_after_discount'] =  round($data['sub_total'] -  $total_discount,2);

        $data['tax'] = $this->taxesValue($data['total_after_discount'], $branch_id);

        $data['total'] =  round($data['total_after_discount'] + $data['tax'],2);

        return $data;
    }

    function discountValue($type, $value, $total)
    {
        if ($type == 'amount') {

            $discount = $value;

        } else {

            $discount = round(($total * $value / 100),2);
        }

        return $discount;
    }

    function taxesValue($total, $branch_id)
    {
        $taxes = TaxesFees::where('active_invoices', 1)->where('branch_id', $branch_id)->get();

        $value = 0;

        foreach($taxes as $tax){

            if ($tax->tax_type == 'amount') {

                $value += $tax->value;

            } else {

                $value += round(($total * $tax->value / 100),2);
            }
        }
        return $value;
    }

    public function validationRules($request){

        $rules = [
            'type'=>'required|string|in:cash,credit',
            'time'=>'required',
            'date'=>'required|date',
            'number_of_items'=>'required|integer|min:1',
            'sales_invoice_id'=>'required|integer|exists:sales_invoices,id',
            'discount_type'=>'required|string|in:percent,amount',
            'discount'=>'required|numeric|min:0',
            'return_part_ids'=>'required',
            'return_part_ids.*'=>'required|integer|exists:parts,id',
        ];

        if($request->has('return_part_ids')){

            foreach ($request['return_part_ids'] as $part_id){

                $rules['sales_invoice_items_id_'.$part_id] = 'required|integer|exists:sales_invoice_items,id';
                $rules['purchase_invoice_id_'.$part_id] = 'required|integer|exists:purchase_invoices,id';
                $rules['return_qty_'.$part_id] = 'required|integer|min:1';
                $rules['last_selling_price_'.$part_id] = 'required|numeric|min:0';
                $rules['selling_price_'.$part_id] = 'required|numeric|min:0';
                $rules['item_discount_type_'.$part_id] = 'required|string|in:percent,amount';
                $rules['item_discount_'.$part_id] = 'required|numeric|min:0';
            }
        }

        return $rules;
    }

    public function affectedPurchaseItem($invoice_item, $return_qty){

        $invoice_item->purchase_qty += $return_qty;
        $invoice_item->save();
    }

    public function affectedPart($part_id, $return_qty, $selling_price){

        $part = Part::find($part_id);
        $part->quantity += $return_qty;
        $part->last_selling_price = $selling_price;
        $part->save();
    }

    public function resetSalesInvoiceQty($invoice)
    {
        foreach($invoice->items as $index=>$oldReturnItem){

            $part = $oldReturnItem->part;

            if($part){
                $part->quantity -= $oldReturnItem->return_qty;
                $part->save();
            }

            $purchase_invoice = $oldReturnItem->purchaseInvoice;

            if($purchase_invoice){

                $purchase_item = $purchase_invoice->items()->where('part_id', $oldReturnItem->part_id)->first();

                if($purchase_item){
                    $purchase_item->purchase_qty -= $oldReturnItem->return_qty;
                    $purchase_item->save();
                }
            }

            $oldReturnItem->forceDelete();
        }

        $this->resetInvoicePoints($invoice);
    }

    public function handlePointsLog($sales_invoice_return)
    {

        if (!$sales_invoice_return->customer_id) {

            return false;
        }

        $SUBLog = __('subtract points from sales invoice return');

        $this->subPoints($sales_invoice_return->customer, $SUBLog, $sales_invoice_return, 'sales_invoice_return_id', 0);

    }

    public function resetInvoicePoints($sales_invoice_return)
    {
        $customer = $sales_invoice_return->customer;

        if ($customer) {

            foreach ($sales_invoice_return->pointsLogs as $pointsLog) {

                if ($pointsLog->type == 'subtraction') {

                    $customer->points += $pointsLog->points;
                }

                $customer->save();

                $pointsLog->delete();
            }
        }
    }
}
