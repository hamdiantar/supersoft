<?php

namespace App\Services;

use App\Models\PurchaseReceiptItem;
use App\Models\SupplyOrder;

class PurchaseReceiptServices
{
    public function savePurchaseReceiptItems ($supplyOrder, $items, $purchaseReceiptId) {

        foreach ($supplyOrder->items as $supplyOrderItem) {

            if ($supplyOrderItem->remaining_quantity_for_accept == 0) {
                continue;
            }

            $data = [

                'purchase_receipt_id'=> $purchaseReceiptId,
                'supply_order_item_id'=> $supplyOrderItem->id,
                'part_id'=> $supplyOrderItem->part_id,
                'part_price_id'=> $supplyOrderItem->part_price_id,
                'total_quantity'=> $supplyOrderItem->quantity,
                'price'=> $supplyOrderItem->price,

                'refused_quantity'=> $items[$supplyOrderItem->id]['refused_quantity'],
                'accepted_quantity'=> $items[$supplyOrderItem->id]['accepted_quantity'],
                'store_id'=> $items[$supplyOrderItem->id]['store_id'],
            ];

            $data['defect_percent'] = round($data['refused_quantity'] * 100 / $supplyOrderItem->quantity,2);

            PurchaseReceiptItem::create($data);
        }
    }

    public function purchaseReceiptData ($requestData) {

        $data = [
            'date' => $requestData['date'],
            'time' => $requestData['time'],
            'supply_order_id' => $requestData['supply_order_id'],
            'notes' => $requestData['notes'],
        ];

        return $data;
    }

    public function validateItemsQuantity ($supplyOrder, $items) {

        $data = ['status'=> true];

        foreach ($supplyOrder->items as $supplyOrderItem) {


            if ($supplyOrderItem->remaining_quantity_for_accept == 0) {
                continue;
            }

            $totalQuantity = $supplyOrderItem->remaining_quantity_for_accept;

            if (!isset($items[$supplyOrderItem->id])) {

                $data['status'] = false;
                $data['message'] = __('sorry, supply order item not valid');
                return $data;
            }

            $itemQuantity = $items[$supplyOrderItem->id];

            if ($itemQuantity['accepted_quantity'] > $totalQuantity) {

                $data['status'] = false;
                $data['message'] = __('sorry, accepted quantity is more than total');
                return $data;
            }

            if ($itemQuantity['refused_quantity'] > $totalQuantity) {

                $data['status'] = false;
                $data['message'] = __('sorry, refused quantity is more than total');
                return $data;
            }

            $refuseAndAcceptQuantity = $itemQuantity['refused_quantity'] + $itemQuantity['accepted_quantity'];

            if ($refuseAndAcceptQuantity > $totalQuantity) {

                $data['status'] = false;
                $data['message'] = __('sorry, refused quantity is more than total');
                return $data;
            }
        }

        return $data;
    }

    public function resetPurchaseReceiptItems ($purchaseReceipt) {

        foreach ($purchaseReceipt->items as $item) {

            $item->delete();
        }
    }
}
