<?php

namespace App\Services;

use App\Models\Part;
use App\Models\PartPrice;
use App\Models\PriceSegment;
use App\Models\PurchaseInvoiceItem;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\TaxesFees;

trait PurchaseInvoiceServices
{
    public function calculateItemTotal($item)
    {
        $data = [

            'part_id' => $item['part_id'],
            'store_id' => $item['store_id'],
            'available_qty' => 0,
            'purchase_qty' => $item['quantity'],
            'quantity' => $item['quantity'],
            'purchase_price' => $item['price'],
            'last_purchase_price' => 0,
            'discount_type' => $item['discount_type'],
            'discount' => $item['discount'],

            'part_price_id' => $item['part_price_id'],
            'part_price_segment_id' => isset($item['part_price_segment_id']) ? $item['part_price_segment_id'] : null,

            'subtotal' => $item['quantity'] * $item['price']
        ];

        $discount_value = $this->discountValue($data['discount_type'], $data['discount'], $data['subtotal']);

        $data['total_after_discount'] = $data['subtotal'] - $discount_value;

        $taxIds = isset($item['taxes']) ? $item['taxes'] : [];

        $data['tax'] = $this->taxesValue($data['total_after_discount'], $data['subtotal'], $taxIds);

        $data['total_after_discount'] += $data['tax'];

        return $data;
    }

    public function prepareInvoiceData($data_request)
    {
        $data = [

            'invoice_number' => $data_request['number'],
            'supplier_id' => $data_request['supplier_id'] ?? null,
            'time' => $data_request['time'],
            'date' => $data_request['date'],
            'number_of_items' => count($data_request['items']),
            'supply_order_id' => isset($data_request['supply_order_id']) && $data_request['invoice_type'] == 'from_supply_order' ? $data_request['supply_order_id'] : null,
            'type' => $data_request['type'],
            'invoice_type' => $data_request['invoice_type'],
            'discount_type' => $data_request['discount_type'],
            'discount' => $data_request['discount'],
            'is_discount_group_added' => 0,
            'status' => $data_request['status']
        ];

        $data['subtotal'] = 0;
        $customer_discount_value = 0;

        foreach ($data_request['items'] as $item) {

            $item_data = $this->calculateItemTotal($item);
            $data['subtotal'] += $item_data['total_after_discount'];
        }

        if ($data_request['supplier_id'] != null && isset($data_request['supplier_discount_active'])) {

            $supplier = Supplier::find($data_request['supplier_id']);

            $data['is_discount_group_added'] = 1;
            $data['discount_group_value'] = $supplier->group_discount;
            $data['discount_group_type'] = $supplier->group_discount_type;

            $customer_discount_value = $this->discountValue($data['discount_group_type'], $data['discount_group_value'], $data['subtotal']);
        }

        $discount = $this->discountValue($data['discount_type'], $data['discount'], $data['subtotal']);

        $data['total_after_discount'] = $data['subtotal'] - ($discount + $customer_discount_value);

        $taxIds = isset($data_request['taxes']) ? $data_request['taxes'] : [];

        $data['tax'] = $this->taxesValue($data['total_after_discount'], $data['subtotal'], $taxIds);

        $additionalPayments = isset($data_request['additional_payments']) ? $data_request['additional_payments'] : [];

        $data['additional_payments'] = $this->taxesValue($data['total_after_discount'], $data['subtotal'], $additionalPayments);

        $data['total'] = $data['total_after_discount'] + $data['tax'] + $data['additional_payments'];

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

    function taxesValue($totalAfterDiscount, $subTotal, $itemTaxes)
    {
        $value = 0;

        $taxes = TaxesFees::whereIn('id', $itemTaxes)->get();

        foreach ($taxes as $tax) {

            if ($tax->execution_time == 'after_discount') {

                $totalUsedInCalculate = $totalAfterDiscount;
            } else {

                $totalUsedInCalculate = $subTotal;
            }

            if ($tax->tax_type == 'amount') {

                $value += $tax->value;

            } else {

                $value += $totalUsedInCalculate * $tax->value / 100;
            }
        }

        return $value;
    }

    public function deletePartsNotInRequest($invoiceItemsIds, $requestItemsIds)
    {

        foreach ($invoiceItemsIds as $id) {

            $invoice_item = PurchaseInvoiceItem::find($id);

            if ($invoice_item && !in_array($id, $requestItemsIds)) {

                $this->restPart($invoice_item->part_id, $invoice_item->purchase_qty);

                $invoice_item->delete();
            }
        }
    }

    public function restPart($part_id, $purchase_qty)
    {

        $part = Part::find($part_id);

        if ($part) {

            $part->quantity -= $purchase_qty;

            if ($part->quantity < 0)
                $part->quantity = 0;

            $part->save();
        }
    }

    public function affectedPart($purchaseInvoiceItem)
    {
        $part = $purchaseInvoiceItem->part;

        if ($part) {
            $part->quantity += $purchaseInvoiceItem->quantity;
            $part->save();
        }

        $this->saveStoreQuantity($purchaseInvoiceItem);
    }

    public function PurchaseInvoiceTaxes($purchaseInvoice, $data)
    {
        $taxes = [];

        if (isset($data['taxes'])) {
            $taxes = array_merge($data['taxes'], $taxes);
        }

        if (isset($data['additional_payments'])) {
            $taxes = array_merge($data['additional_payments'], $taxes);
        }

        if (!empty($taxes)) {
            $purchaseInvoice->taxes()->attach($taxes);
        }
    }

    function resetPurchaseInvoiceDataItems($purchaseInvoice)
    {

        foreach ($purchaseInvoice->items as $item) {
            $item->taxes()->detach();
            $item->delete();
        }

        $purchaseInvoice->taxes()->detach();
        $purchaseInvoice->purchaseReceipts()->detach();
    }

    public function saveStoreQuantity($item)
    {
        $part = $item->part;

        $partStorePivot = $part->stores()->where('store_id', $item->store_id)->first();

        if (!$partStorePivot) {
            $part->stores()->attach($item->store_id);
        }

        $partStorePivot = $part->load('stores')->stores()->where('store_id', $item->store_id)->first();

        $unitQuantity = $item->partPrice ? $item->partPrice->quantity : 1;

        $requestedQuantity = $unitQuantity * $item->quantity;

        $partStorePivot->pivot->quantity += $requestedQuantity;

        $partStorePivot->pivot->save();

        return true;
    }

}
