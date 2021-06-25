<?php


namespace App\Services;


use App\Models\Part;
use App\Models\PartPrice;

class DamagedStockServices
{
    public function deleteItems ($damagedStock) {

        foreach ($damagedStock->items as $item) {

            $item->delete();
        }

        return true;
    }

    public function addEmployees ($damageStock , $employees) {

        $total = $damageStock->total;

        $damageStock->employees()->detach();

        foreach ($employees as $employee) {

            $percent = $employee['percent'];

            $amount = round($total * $percent / 100, 2);

            $damageStock->employees()->attach([ $employee['employee_id'] => [ 'percent' => $percent, 'amount'=> $amount ] ]);
        }
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
