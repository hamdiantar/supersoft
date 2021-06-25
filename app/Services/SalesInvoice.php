<?php


namespace App\Services;


use App\Models\Customer;
use App\Models\Part;
use App\Models\PointRule;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoiceItems;
use App\Models\Setting;
use App\Models\TaxesFees;

trait SalesInvoice
{
    public function calculateItemTotal($request, $index, $part_id, $purchase_invoice)
    {
        $part_index = $request['index'][$index];

        $data = [
            'purchase_invoice_id' => $purchase_invoice->id,
            'part_id' => $request['part_ids'][$index],
//            'available_qty' => $purchase_invoice->purchase_qty,
            'sold_qty' => $request['sold_qty'][$index],
            'last_selling_price' => $request['last_selling_price'][$index],
            'selling_price' => $request['selling_price'][$index],
            'discount_type' => $request['item_discount_type_' . $part_index],
            'discount' => $request['item_discount'][$index]
        ];

//       on repeat item save discount one time
        if ($data['discount_type'] == 'amount' && isset($request['repeat_count']) && $request['repeat_count'] == 1) {

            $data['discount'] = 0;
        }

        $data['sub_total'] = $request['sold_qty'][$index] * $request['selling_price'][$index];

        $discount_value = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - $discount_value;

        if ($data['total_after_discount'] < 0) {

            $data['total_after_discount'] = 0;
        }

        $data['total'] = $data['total_after_discount'];

        return $data;
    }

    public function prepareInvoiceData($request)
    {
        $data = [
            'customer_id' => $request['customer_id'],
            'time' => $request['time'],
            'date' => $request['date'],
            'number_of_items' => count($request['part_ids']), // $request['number_of_items'],
            'type' => $request['type'],
            'discount_type' => $request['discount_type'],
            'discount' => $request['discount'],
            'customer_discount_status' => 0,
            'points_rule_id'=> null,
            'points_discount'=> 0,
        ];

        $data['sub_total'] = 0;
        $data['customer_discount'] = 0;
        $data['customer_discount_type'] = 'amount';
        $customer_discount_value = 0;
        $points_discount = 0;

        $setting_sell_From_invoice_status = $this->settingSellFromInvoiceStatus($request['branch_id']);

        foreach ($request['part_ids'] as $index => $part_id) {

            $purchase_invoice = $this->getPurchaseInvoice($setting_sell_From_invoice_status, $request['branch_id'],
                $request['purchase_invoice_id'][$index], $part_id);

            $item_data = $this->calculateItemTotal($request, $index, $part_id, $purchase_invoice);
            $data['sub_total'] += $item_data['total'];
        }

        if ($request['customer_id']) {

            $customer = Customer::find($request['customer_id']);

            if ($customer && $customer->customerCategory) {

                $data['customer_discount'] = $customer->customerCategory->sales_discount;
                $data['customer_discount_type'] = $customer->customerCategory->sales_discount_type;
            }
        }

        if (isset($request['customer_discount_check'])) {

            $data['customer_discount_status'] = 1;

            $customer_discount_value = $this->discountValue($data['customer_discount_type'], $data['customer_discount'],
                $data['sub_total']);
        }

        if ($request['points_rule_id']) {

            $pointRule = PointRule::find($request['points_rule_id']);

            $points_discount += $pointRule ? $pointRule->amount : 0;

            $data['points_discount'] = $points_discount;
            $data['points_rule_id'] = $request['points_rule_id'];
        }

        $data['discount'] = $this->discountValue($request['discount_type'], $request['discount'], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - ($data['discount'] + $customer_discount_value + $points_discount);

        if ($data['total_after_discount'] < 0) {

            $data['total_after_discount'] = 0;
        }

        $data['tax'] = $this->taxesValue($data['total_after_discount'], $request);

        $data['total'] = $data['total_after_discount'] + $data['tax'];

        $data['discount'] = $request['discount'];

//        dd($data);

        return $data;
    }

    function discountValue($type, $value, $total)
    {
        if ($type == 'amount') {

            $discount = $value;

        } else {

            $discount = $total * $value / 100;
        }

        return $discount;
    }

    function taxesValue($total, $request)
    {
        $taxes = TaxesFees::where('active_invoices', 1)->where('branch_id', $request['branch_id'])->get();

        $value = 0;

        foreach ($taxes as $tax) {

            if ($tax->tax_type == 'amount') {

                $value += $tax->value;

            } else {

                $value += $total * $tax->value / 100;
            }
        }
        return $value;
    }

    public function customerBalance($request, $sales_invoice)
    {

        if ($request->has('customer_id') && $request['customer_id'] != null) {

            $customer = Customer::find($request['customer_id']);
            $customer->balance_to += $sales_invoice->total;
            $customer->save();
        }
    }

    public function affectedPurchaseItem($invoice_item, $sold_qty)
    {

        if ($invoice_item) {

            $invoice_item->purchase_qty -= $sold_qty;
            $invoice_item->save();
        }
    }

    public function affectedPart($part_id, $sold_qty, $selling_price)
    {
        $part = Part::find($part_id);

        if ($part) {

            $part->quantity -= $sold_qty;
            $part->last_selling_price = $selling_price;
            $part->save();
        }
    }

    public function validationRules()
    {
        $rules = [

            'customer_id' => 'nullable|integer|exists:customers,id',
            'date' => 'required|date',
            'time' => 'required',
            'part_ids' => 'required',
            'part_ids.*' => 'required|integer|exists:parts,id',

            'purchase_invoice_id' => 'required',
            'purchase_invoice_id.*' => 'required|integer|exists:purchase_invoices,id',

            'available_qy' => 'required',
            'available_qy.*' => 'required|integer|min:1',

            'sold_qty' => 'required',
            'sold_qty.*' => 'required|integer|min:1',

            'last_selling_price' => 'required',
            'last_selling_price.*' => 'required|numeric|min:0',

            'selling_price' => 'required',
            'selling_price.*' => 'required|numeric|min:1',

            'item_discount_type' => 'nullable',
            'item_discount_type.*' => 'nullable|string|in:amount,percent',

            'item_discount' => 'nullable',
            'item_discount.*' => 'nullable|numeric|min:0',

            'parts_count' => 'required|integer|min:1',
            'invoice_tax' => 'required|numeric|min:0',

            'discount_type' => 'required|string|in:amount,percent',
            'discount' => 'nullable|numeric|min:0',
            'type' => 'required|string|in:cash,credit'
        ];

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        return $rules;
    }

    public function resetSalesInvoiceQty($salesInvoice)
    {
        foreach ($salesInvoice->items as $index => $item) {

            $part = $item->part;

            if ($part) {
                $part->quantity += $item->sold_qty;
                $part->save();
            }

            $purchase_invoice = $item->purchaseInvoice;

            if ($purchase_invoice) {

                $purchase_item = $purchase_invoice->items()->where('part_id', $item->part_id)->first();

                if ($purchase_item) {
                    $purchase_item->purchase_qty += $item->sold_qty;
                    $purchase_item->save();
                }
            }

            $item->forceDelete();
        }

//      RESET LOG POINTS
        $this->resetInvoicePoints($salesInvoice);
    }

    public function resetInvoicePoints($salesInvoice)
    {

        $customer = $salesInvoice->customer;

        if ($customer) {

            foreach ($salesInvoice->pointsLogs as $pointsLog) {

                if ($pointsLog->type == 'additional') {

                    $customer->points -= $pointsLog->points;
                }

                if ($pointsLog->type == 'subtraction') {

                    $customer->points += $pointsLog->points;
                }

                $customer->save();

                $pointsLog->delete();
            }
        }
    }

    public function handlePointsLog($salesInvoice)
    {

        if (!$salesInvoice->customer_id) {

            return false;
        }

        $addLog = __('add points from sales invoice');
        $SUBLog = __('subtract points from sales invoice');
        $this->addPoints($salesInvoice->customer, $addLog, $salesInvoice, 'sales_invoice_id');

        if ($salesInvoice->points_rule_id) {

            $pointRule = PointRule::find($salesInvoice->points_rule_id);

            $points = $pointRule ? $pointRule->points : 0;

            $this->subPoints($salesInvoice->customer, $SUBLog, $salesInvoice, 'sales_invoice_id', $points);
        }
    }

//    public function getPurchaseInvoice($setting_sell_invoice_status, $branch_id, $purchase_invoice_id, $part_id){
//
//        $purchase_invoice = PurchaseInvoice::find($purchase_invoice_id);
//
//        if (!$purchase_invoice) {
//
//            $sortType = $setting_sell_invoice_status == 'old' ? 'asc' : 'desc';
//
//            $purchase_invoice = PurchaseInvoice::where('branch_id', $branch_id)
//                ->orderBy('date', $sortType)
//                ->whereHas('items', function($q) use($part_id){
//
//                    $q->where('part_id', $part_id)->where('purchase_qty','!=', 0);
//
//                })->first();
//        }
//
//        return $purchase_invoice;
//    }
//
//    public function getNextPurchaseInvoices($setting_sell_invoice_status, $branch_id, $current_purchase_invoice_id, $part_id){
//
//        $sortType = $setting_sell_invoice_status == 'old' ? 'asc' : 'desc';
//
//        $purchase_invoices = PurchaseInvoice::where('branch_id', $branch_id)
//            ->orderBy('date', $sortType)
//            ->whereHas('items', function($q) use($part_id){
//
//                $q->where('part_id', $part_id)->where('purchase_qty','!=', 0);
//
//            })->get();
//
//        return $purchase_invoices;
//    }

//   when request more quantity of item, more than in purchase invoice selected
    public function repeatPartItem($data, $next_purchase_invoices, $index, $part_id, $sales_invoice)
    {

        $requestQty = $data['sold_qty'][$index];

        $data['repeat_count'] = 0;

        foreach ($next_purchase_invoices as $key => $next_purchase_invoice) {

            $data['repeat_count'] = $key;

            $next_invoice_item = $next_purchase_invoice->items()->where('part_id', $part_id)->first();

            if ($next_invoice_item && $requestQty > 0) {

                $thisInvoiceQty = $next_invoice_item->purchase_qty;

                $qtyGetFromThisInvoice = $thisInvoiceQty >= $requestQty ? $requestQty : $thisInvoiceQty;

                $data['sold_qty'][$index] = $qtyGetFromThisInvoice;

                $item_data = $this->calculateItemTotal($data, $index, $part_id, $next_purchase_invoice);

                $item_data['sales_invoice_id'] = $sales_invoice->id;
                $item_data['available_qty'] = $next_invoice_item->purchase_qty;

                $item = SalesInvoiceItems::create($item_data);

                $this->affectedPurchaseItem($next_invoice_item, $qtyGetFromThisInvoice);

                $this->affectedPart($part_id, $qtyGetFromThisInvoice, $data['selling_price'][$index]);

                $requestQty -= $qtyGetFromThisInvoice;
            }

            if ($requestQty <= 0) {
                break;
            }
        }
    }

    public function requestedPartQty($part, $requestQty, $itemsQty)
    {

        if (in_array($part->id, $itemsQty)) {

            $itemsQty[$part->id]['requestQty'] += $requestQty;
            return $itemsQty;
        }

        $itemsQty[$part->id] = [

            'partQty' => $part->quantity,
            'requestQty' => $requestQty,
        ];

        return $itemsQty;
    }
}
