<?php


namespace App\Services;


use App\Models\Part;
use App\Models\PartPrice;
use App\Models\PriceSegment;
use App\Models\PurchaseInvoiceItem;
use App\Models\Supplier;
use App\Models\TaxesFees;

trait PurchaseInvoiceServices
{

    public function calculateItemTotal($item)
    {
        $part = Part::find($item['id']);

        $data = [

            'part_id' => $item['id'],
            'store_id' => $item['store_id'],
            'available_qty' => $part->quantity,
            'purchase_qty' => $item['purchase_qty'],
            'quantity' => $item['purchase_qty'],
            'purchase_price' => $item['purchase_price'],
            'last_purchase_price' => $part->last_purchase_price,
            'discount_type' => $item['discount_type'],
            'discount' => $item['discount'],

            'part_price_id' => $item['part_price_id'],
            'part_price_segment_id' => $item['part_price_segment_id'],

        ];

        $data['subtotal'] = $data['purchase_qty'] * $data['purchase_price'];

        $discount_value = $this->discountValue($data['discount_type'], $data['discount'], $data['subtotal']);

        $data['total_after_discount'] = $data['subtotal'] - $discount_value;

        $taxIds = isset($item['taxes']) ? $item['taxes'] : [];

        $data['tax'] = $this->taxesValue($data['total_after_discount'], $part->branch_id, $taxIds, 1);

        $data['total_after_discount'] += $data['tax'];

        return $data;
    }

    public function prepareInvoiceData($data_request)
    {
        $data = [

            'branch_id' => $data_request['branch_id'],
            'invoice_number' => $data_request['invoice_number'],
            'supplier_id' => $data_request['supplier_id'] ?? null,
            'time' => $data_request['time'],
            'date' => $data_request['date'],
            'number_of_items' => count($data_request['items']), // $request['number_of_items'],
            'type' => $data_request['type'],
            'discount_type' => $data_request['discount_type'],
            'discount' => $data_request['discount'],
            'is_discount_group_added' => 0,
        ];

        $data['subtotal'] = 0;
        $data['customer_discount'] = 0;
        $data['customer_discount_type'] = 'amount';
        $customer_discount_value = 0;

        foreach ($data_request['items'] as $item) {

            $item_data = $this->calculateItemTotal($item);
            $data['subtotal'] += $item_data['total_after_discount'];
        }

        if ($data_request['supplier_id'] != null) {

            $supplier = Supplier::find($data_request['supplier_id']);

            if ($supplier && $supplier->suppliersGroup) {

                $data['discount_group_value'] = $supplier->suppliersGroup->discount;
                $data['discount_group_type'] = $supplier->suppliersGroup->discount_type;
            }
        }

        if (isset($data_request['supplier_discount_check'])) {

            $data['is_discount_group_added'] = 1;

            $customer_discount_value = $this->discountValue($data['discount_group_type'], $data['discount_group_value'], $data['subtotal']);
        }

        $discount = $this->discountValue($data['discount_type'], $data['discount'], $data['subtotal']);

        $data['total_after_discount'] = $data['subtotal'] - ($discount + $customer_discount_value);

        $taxIds = isset($data_request['taxes']) ? $data_request['taxes'] : [];

        $data['tax'] = $this->taxesValue($data['total_after_discount'], $data_request['branch_id'], $taxIds, 0);

        $data['total'] = $data['total_after_discount'] + $data['tax'];

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

    function taxesValue($total, $branch_id, $taxIds, $on_parts)
    {
        $taxes = TaxesFees::where('on_parts', $on_parts)->where('active_purchase_invoice', 1)->where('branch_id', $branch_id)->get();

        $value = 0;

        foreach ($taxes as $tax) {

            if (in_array($tax->id, $taxIds)) {

                if ($tax->tax_type == 'amount') {

                    $value += $tax->value;

                } else {

                    $value += $total * $tax->value / 100;
                }
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

    public function affectedPart($part_id, $purchase_qty, $purchase_price, $part_price_id = null)
    {
        $part = Part::find($part_id);
        $partPrice = PartPrice::find($part_price_id);

        if ($part) {

            $part->quantity += $purchase_qty;
            $part->save();
        }

        if ($partPrice) {

            $partPrice->last_purchase_price = $purchase_price;
            $partPrice->save();
        }
    }
}
