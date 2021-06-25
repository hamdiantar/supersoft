<?php


namespace App\Services;


use App\Models\Part;
use App\Models\PartPrice;

class SettlementServices
{

    public function deleteItems ($settlement) {

        foreach ($settlement->items as $item) {

            $item->delete();
        }

        return true;
    }

    public function checkMaxQuantityOfItem ($items) {

        $status = false;

        foreach ($items as $item) {

            $part = Part::find($item['part_id']);

            $store = $part->stores()->where('store_id', $item['store_id'])->first();

            $partPrice = PartPrice::find($item['part_price_id']);

            $requestedQuantity = $partPrice->quantity * $item['quantity'];

            if (!$store || !$partPrice || $requestedQuantity > $store->pivot->quantity) {

                $status = true;
                return $status;
            }
        }

        return $status;
    }
}
