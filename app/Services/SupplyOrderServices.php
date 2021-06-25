<?php


namespace App\Services;


use App\Models\Supplier;
use App\Models\TaxesFees;

class SupplyOrderServices
{

    public function supplyOrderItemData($item)
    {
        $data = [
            'part_id' => $item['part_id'],
            'part_price_id' => $item['part_price_id'],
            'part_price_segment_id' => isset($item['part_price_segment_id']) ? $item['part_price_segment_id'] : null,
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'discount' => $item['discount'],
            'discount_type' => $item['discount_type'],
            'sub_total' => $item['quantity'] * $item['price']
        ];

        $discountValue = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - $discountValue;

        $itemTaxes = isset($item['taxes']) ? $item['taxes'] : [];

        $data['tax'] = $this->taxesValue($data['total_after_discount'], $data['sub_total'], $itemTaxes);

        $data['total'] = $data['total_after_discount'] + $data['tax'];

        return $data;
    }

    public function supplyOrderData($requestData)
    {
        $data = [
            'number' => $requestData['number'],
            'type' => $requestData['type'],
            'date' => $requestData['date'],
            'time' => $requestData['time'],
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

                $itemData = $this->supplyOrderItemData($item);
                $data['sub_total'] += $itemData['total'];

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

    function resetSupplyOrderDataItems($supplyOrder)
    {

        foreach ($supplyOrder->items as $item) {
            $item->delete();
        }

        $supplyOrder->taxes()->detach();
        $supplyOrder->purchaseQuotations()->detach();
    }

    public function supplyOrderTaxes($supplyOrder, $data)
    {

        $taxes = [];

        if (isset($data['taxes'])) {
            $taxes = array_merge($data['taxes'], $taxes);
        }

        if (isset($data['additional_payments'])) {
            $taxes = array_merge($data['additional_payments'], $taxes);
        }

        if (!empty($taxes)) {
            $supplyOrder->taxes()->attach($taxes);
        }
    }

    public function supplierDiscount($supplier, $total)
    {
        $supplier_discount = $supplier->group_discount;
        $supplier_discount_type = $supplier->group_discount_type;

        return $this->discountValue($supplier_discount_type, $supplier_discount, $total);
    }

}
