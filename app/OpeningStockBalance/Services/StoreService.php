<?php
namespace App\OpeningStockBalance\Services;

use Exception;
use App\Models\PartStore;
use App\OpeningStockBalance\Models\OpeningBalanceItems;

class StoreService {
    protected $item ,$qnt ,$part_store;

    function __construct(OpeningBalanceItems $item) {
        $this->item = $item;
        $this->qnt = $this->item->quantity * $this->item->default_unit_quantity;
        $this->part_store = PartStore::firstOrCreate(['part_id' => $this->item->part_id ,'store_id' => $this->item->store_id]);
    }

    function create_store_quantity() {
        $this->part_store->quantity += $this->qnt;
        $this->part_store->save();
    }

    function delete_store_quantity() {
        if ($this->part_store->quantity < $this->qnt) throw new Exception(__('opening-balance.store-quantity-unsatisfied'));
        $this->part_store->quantity -= $this->qnt;
        $this->part_store->save();
    }

    function update_store_quantity($requested_converted_qnt) {
        $old_converted_qnt = $this->item->quantity * $this->item->default_unit_quantity;
        if ($requested_converted_qnt > $old_converted_qnt) {
            // here means we will increment store with difference between (requested & old)
            $this->part_store->quantity += $requested_converted_qnt - $old_converted_qnt;
        } else {
            // here means we will decrement store with difference between (requested & old)
            $difference = $old_converted_qnt - $requested_converted_qnt;
            if ($difference > 0 && $this->part_store->quantity < $difference)
                throw new Exception(__('opening-balance.store-quantity-unsatisfied'));
            $this->part_store->quantity -= $difference;
        }
        $this->part_store->save();
    }
}