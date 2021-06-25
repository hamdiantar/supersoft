<?php


namespace App\Services;


use App\Models\Part;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItems;
use App\Models\Setting;
use App\Models\TaxesFees;

trait QuotationToSalesServices
{
    public function calculateItemTotal($item)
    {
        $data = [
            'purchase_invoice_id' => $item['purchase_invoice_id'],
            'part_id' => $item['part_id'],
            'sold_qty' => $item['sold_qty'],
            'last_selling_price' => $item['selling_price'],
            'selling_price' => $item['selling_price'],
            'discount_type' => $item['discount_type'],
            'discount' => $item['discount']
        ];

        if ($data['discount_type'] == 'amount' && isset($request['repeat_count']) && $request['repeat_count'] >= 1) {

            $data['discount'] = 0;
        }

        $data['sub_total'] = $data['sold_qty'] * $data['selling_price'];

        $discount_value = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - $discount_value;

        $data['total'] = $data['total_after_discount'];

        return $data;
    }

    public function prepareInvoiceData($quotation, $quotationItems)
    {
        $data = [
            'customer_id' => $quotation['customer_id'],
            'time' => $quotation['time'],
            'date' => $quotation['date'],
            'number_of_items' => count($quotationItems),
            'type' => 'cash',
            'discount_type' => $quotation['discount_type'],
            'discount' => $quotation['discount'],
            'customer_discount_status' => 0,
            'customer_discount' => 0,
            'customer_discount_type' => 'amount',
            'branch_id'=> $quotation->branch_id,
            'created_by'=> auth()->id()
        ];

        $data['sub_total'] = 0;

        foreach ($quotationItems as $index => $quotationItem) {

            $item_data = $this->calculateItemTotal($quotationItem);

            $data['sub_total'] += $item_data['total'];
        }

        $discountValue = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - $discountValue;

        $data['tax'] = $quotation->tax; //$this->taxesValue($data['total_after_discount'], $quotation);

        $data['total'] = $data['total_after_discount'] + $data['tax'];

        return $data;
    }

    public function generateInvoiceNumber($branch_id)
    {

        $last_invoice = SalesInvoice::where('branch_id', $branch_id)->latest('created_at')->first();

        $invoice_number = $last_invoice ? $last_invoice->invoice_number + 1 : 1;

        return $invoice_number;
    }

    public function prepareQuotationItems($quotationItems, $branchId)
    {
        $items = [];

        foreach ($quotationItems as $quotationItem) {

            $items[$quotationItem->model_id]['purchase_invoice_id'] = $quotationItem->purchase_invoice_id;
            $items[$quotationItem->model_id]['part_id'] = $quotationItem->model_id;
            $items[$quotationItem->model_id]['sold_qty'] = $quotationItem->qty;
            $items[$quotationItem->model_id]['selling_price'] = $quotationItem->price;
            $items[$quotationItem->model_id]['discount_type'] = $quotationItem->discount_type;
            $items[$quotationItem->model_id]['discount'] = $quotationItem->discount;
        }

        return $items;
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

    public function saveInvoiceItems($quotationItems, $salesInvoiceId, $branchId)
    {
        foreach ($quotationItems as $quotationItem) {

            $purchase_invoice = PurchaseInvoice::find($quotationItem['purchase_invoice_id']);

            $invoice_item = $purchase_invoice->items()->where('part_id', $quotationItem['part_id'])->first();

//           REPEAT ITEM
            if ($invoice_item->purchase_qty < $quotationItem['sold_qty']) {

                $next_purchase_invoices = $this->getNextPurchaseInvoices($branchId, $quotationItem['part_id']);

                $this->repeatPartItem($quotationItem, $next_purchase_invoices, $salesInvoiceId);

                continue;
            }

            $invoiceItemData = $this->calculateItemTotal($quotationItem);

            $invoiceItemData['sales_invoice_id'] = $salesInvoiceId;

            $invoiceItemData['available_qty'] = $invoice_item->purchase_qty;

            $item = SalesInvoiceItems::create($invoiceItemData);

            $this->affectedPurchaseItem($invoice_item, $quotationItem['sold_qty']);

            $this->affectedPart($quotationItem['part_id'], $quotationItem['sold_qty'], $quotationItem['selling_price']);
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

    public function checkValidation($quotationItems)
    {
        $messages = [];

        foreach ($quotationItems as $index => $quotationItem) {

            $purchaseInvoice = PurchaseInvoice::find($quotationItem['purchase_invoice_id']);

            $part = Part::find($quotationItem['part_id']);

            if (!$purchaseInvoice) {
                $messages[] = __('purchase invoice not found');
            }

            if (!$part) {
                $messages[] = __('part not found');
            }

            if ($part->quantity < $quotationItem['sold_qty']) {

                $messages[] = __('quantity not available');
            }
        }

        return $messages;
    }

    public function checkPurchaseInvoiceHasQuantity($purchaseInvoiceId, $part_id, $qty)
    {
        $purchase_invoice = PurchaseInvoice::find($purchaseInvoiceId);

        $invoice_item = $purchase_invoice->items()->where('part_id', $part_id)->first();

        if ($invoice_item->sold_qty < $qty) {

            return false;
        }

        return true;
    }

    public function settingSellFromInvoiceStatus($branch_id) {

        $setting = Setting::where('branch_id', $branch_id)->first();

        $setting_sell_From_invoice_status = 'old';

        if($setting){

            $setting_sell_From_invoice_status = $setting->sell_from_invoice_status;
        }

        return $setting_sell_From_invoice_status;
    }

    public function getNextPurchaseInvoices($branch_id, $part_id) {

        $setting_sell_invoice_status = $this->settingSellFromInvoiceStatus($branch_id);

        $sortType = $setting_sell_invoice_status == 'old' ? 'asc' : 'desc';

        $purchase_invoices = PurchaseInvoice::where('branch_id', $branch_id)
            ->orderBy('date', $sortType)
            ->whereHas('items', function($q) use($part_id){

                $q->where('part_id', $part_id)->where('purchase_qty','!=', 0);

            })->get();

        return $purchase_invoices;
    }

    public function repeatPartItem($quotationItem, $next_purchase_invoices, $salesInvoiceId) {

        $requestQty = $quotationItem['sold_qty'];

        $data['repeat_count'] = 0;

        foreach ($next_purchase_invoices as $key => $next_purchase_invoice) {

            $quotationItem['repeat_count'] = $key;

            $next_invoice_item = $next_purchase_invoice->items()->where('part_id', $quotationItem['part_id'])->first();

            if ($next_invoice_item && $requestQty > 0) {

                $thisInvoiceQty = $next_invoice_item->purchase_qty;

                $qtyGetFromThisInvoice = $thisInvoiceQty >= $requestQty ? $requestQty : $thisInvoiceQty;

                $quotationItem['sold_qty'] = $qtyGetFromThisInvoice;

                $invoiceItemData = $this->calculateItemTotal($quotationItem);

                $invoiceItemData['sales_invoice_id'] = $salesInvoiceId;

                $invoiceItemData['available_qty'] = $next_invoice_item->purchase_qty;

                $item = SalesInvoiceItems::create($invoiceItemData);

                $this->affectedPurchaseItem($next_invoice_item, $qtyGetFromThisInvoice);

                $this->affectedPart($quotationItem['part_id'], $qtyGetFromThisInvoice, $quotationItem['selling_price']);

                $requestQty -= $qtyGetFromThisInvoice;
            }

            if ($requestQty <= 0) {
                break;
            }
        }
    }
}
