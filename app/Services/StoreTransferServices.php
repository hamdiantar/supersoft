<?php


namespace App\Services;


use App\Models\Part;
use App\Models\PartPrice;
use App\Models\PartPriceSegment;
use App\Models\StoreTransfer;

class StoreTransferServices
{

    public function storeTransferItemData($item)
    {
        $partPrice = PartPrice::find($item['part_price_id']);

        $price = $partPrice ? $partPrice->purchase_price : 0;

        if (isset($item['part_price_segment_id'])) {

            $partPriceSegment = PartPriceSegment::find($item['part_price_segment_id']);
            $price = $partPriceSegment ? $partPriceSegment->purchase_price : 0;
        }

        $data = [

            'part_id' => $item['part_id'],
            'part_price_id' => $item['part_price_id'],
            'quantity' => $item['quantity'],
            'price' => $price,
            'total' => $price * $item['quantity'],
            'part_price_segment_id' => isset($item['part_price_segment_id']) ? $item['part_price_segment_id'] : null,
        ];

        return $data;
    }

    public function storeTransferData($requestData)
    {

        $data = [

            'transfer_date' => $requestData['date'],
            'time' => $requestData['time'],
            'store_from_id' => $requestData['store_from_id'],
            'store_to_id' => $requestData['store_to_id'],
            'description' => isset($requestData['description']) ? $requestData['description'] : null ,
            'total'=> 0,
        ];

        if (isset($requestData['items'])) {

            foreach ($requestData['items'] as $item) {

                $itemData = $this->storeTransferItemData($item);

                $data['total'] += $itemData['total'];
            }
        }

        return $data;
    }

    public function checkMaxQuantityOfItem ($items, $store_id) {

        $status = false;

        foreach ($items as $item) {

            $part = Part::find($item['part_id']);

            $store = $part->stores()->where('store_id', $store_id)->first();

            $partPrice = PartPrice::find($item['part_price_id']);

            $requestedQuantity = $partPrice->quantity * $item['quantity'];

            if (!$store || !$partPrice || $requestedQuantity > $store->pivot->quantity) {

               $status = true;
               return $status;
            }
        }

        return $status;
    }

    public function resetItems ($storeTransfer) {

        foreach ($storeTransfer->items as $item) {

            $item->forceDelete();
        }
    }
}
