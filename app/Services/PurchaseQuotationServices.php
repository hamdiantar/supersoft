<?php

namespace App\Services;

use App\Models\Supplier;
use App\Models\TaxesFees;

class PurchaseQuotationServices
{
    public function PurchaseQuotationItemData($item)
    {
        $data = [
            'part_id' => $item['part_id'],
            'part_price_id' => $item['part_price_id'],
            'part_price_segment_id' => isset($item['part_price_segment_id']) ? $item['part_price_segment_id'] : null,
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'discount' => $item['discount'],
            'discount_type' => $item['discount_type'],
            'active' => isset($item['checked']) ? 1 : 0,
            'sub_total' => $item['quantity'] * $item['price']
        ];

        $discountValue = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - $discountValue;

        $itemTaxes = isset($item['taxes']) ? $item['taxes'] : [];

        $data['tax'] = $this->taxesValue($data['total_after_discount'], $data['sub_total'], $itemTaxes);

        $data['total'] = $data['total_after_discount'] + $data['tax'];

        return $data;
    }

    public function PurchaseQuotationData($requestData)
    {
        $data = [
            'number' => $requestData['number'],
            'type' => $requestData['type'],
            'date' => $requestData['date'],
            'time' => $requestData['time'],
            'supply_date_from' => $requestData['supply_date_from'],
            'supply_date_to' => $requestData['supply_date_to'],
            'supplier_id' => $requestData['supplier_id'],
            'purchase_request_id' => isset($requestData['purchase_request_id']) && $requestData['type'] == 'from_purchase_request' ? $requestData['purchase_request_id'] : null,
            'discount' => $requestData['discount'],
            'discount_type' => $requestData['discount_type'],
            'supplier_discount_active'=> 0,
        ];

        if (isset($requestData['status'])) {
            $data['status'] = $requestData['status'];
        }

        $data['sub_total'] = 0;
        $supplier_discount = 0;

        if (isset($requestData['items'])) {

            foreach ($requestData['items'] as $item) {

                if (isset($item['checked'])) {

                    $itemData = $this->PurchaseQuotationItemData($item);
                    $data['sub_total'] += $itemData['total'];
                }
            }
        }

        if (isset($requestData['supplier_discount_active'])) {

            $supplier = Supplier::find($data['supplier_id']);

            $data['supplier_discount_active'] = 1;
            $data['supplier_discount'] = $supplier->group_discount;
            $data['supplier_discount_type'] = $supplier->group_discount_type;

            $supplier_discount = $this->supplierDiscount($supplier, $data['sub_total']);
        }

        $discountValue = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $totalDiscount = $discountValue + $supplier_discount;

        $data['total_after_discount'] = $data['sub_total'] - $totalDiscount;

        $itemTaxes = isset($requestData['taxes']) ? $requestData['taxes'] : [];

        $data['tax'] = $this->taxesValue($data['total_after_discount'], $data['sub_total'], $itemTaxes);

        $additionalPayments = isset($requestData['additional_payments']) ? $requestData['additional_payments'] : [];

        $data['additional_payments'] = $this->taxesValue($data['total_after_discount'], $data['sub_total'], $additionalPayments);

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

        foreach ($itemTaxes as $tax_id) {

            $tax = TaxesFees::find($tax_id);

            if ($tax) {

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
        }
        return $value;
    }

    function resetPurchaseQuotationItems($purchaseQuotation)
    {

        foreach ($purchaseQuotation->items as $item) {
            $item->delete();
        }

        $purchaseQuotation->taxes()->detach();
    }

    public function getPartTypes($partMainTypes, $part_id)
    {

        $data = [];

        $level = 1;

        foreach ($partMainTypes as $type) {

            $data[$type->id] = $level . '.' . $type->type;
            $this->getChildrenTypes($type, $part_id, $level, $data);
            $level++;
        }

        return $data;
    }

    public function getChildrenTypes($partType, $part_id, $level, &$data)
    {
        $counter = 1;

        $types = $partType->children()->whereHas('allParts', function ($q) use ($part_id) {
            $q->where('part_id', $part_id);
        })->get();

        $depthCounter = $level . '.' . $counter;

        foreach ($types as $type) {
            $data[$type->id] = $depthCounter . '.' . $type->type;

            if ($type->children) {
                $this->getChildrenTypes($type, $part_id, $depthCounter, $data);
            }

            $counter++;
        }
    }

    public function purchaseQuotationTaxes($purchaseQuotation, $data)
    {

        $taxes = [];

        if (isset($data['taxes'])) {
            $taxes = array_merge($data['taxes'], $taxes);
        }

        if (isset($data['additional_payments'])) {
            $taxes = array_merge($data['additional_payments'], $taxes);
        }

        if (!empty($taxes)) {
            $purchaseQuotation->taxes()->attach($taxes);
        }
    }

    public function supplierDiscount($supplier, $total)
    {
        $supplier_discount = $supplier->group_discount;
        $supplier_discount_type = $supplier->group_discount_type;

        return $this->discountValue($supplier_discount_type, $supplier_discount, $total);
    }
}
