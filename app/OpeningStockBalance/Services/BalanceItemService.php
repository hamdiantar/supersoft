<?php
namespace App\OpeningStockBalance\Services;

use App\OpeningStockBalance\Models\OpeningBalanceItems;

class BalanceItemService {
    function create_item(int $opening_balance_id ,array $row) {
        $row_data = [
            'opening_balance_id' => $opening_balance_id,
            'part_id' => isset($row['part']) ? $row['part'] : NULL,
            'part_price_id' => isset($row['unit_id']) ? $row['unit_id'] : NULL,
            'part_price_price_segment_id' => isset($row['price_segment_id']) ? $row['price_segment_id'] : NULL,
            'quantity' => isset($row['quantity']) ? $row['quantity'] : 0,
            'default_unit_quantity' => isset($row['default_quantity']) ? $row['default_quantity'] : 0,
            'buy_price' => isset($row['buy_price']) ? $row['buy_price'] : 0,
            'store_id' => isset($row['store_id']) ? $row['store_id'] : 0
        ];
        $item = OpeningBalanceItems::create($row_data);
//        (new StoreService($item))->create_store_quantity();
    }

    function update_item(OpeningBalanceItems $item ,$row) {
//        if (isset($row['default_quantity']) && isset($row['quantity']) && $item->quantity != $row['quantity'])
//            (new StoreService($item))->update_store_quantity($row['quantity'] * $row['default_quantity']);
        $item->update([
            'part_price_id' => isset($row['unit_id']) ? $row['unit_id'] : $item->part_price_id,
            'part_price_price_segment_id' => isset($row['price_segment_id']) ? $row['price_segment_id'] : $item->part_price_price_segment_id,
            'quantity' => isset($row['quantity']) ? $row['quantity'] : $item->quantity,
            'default_unit_quantity' => isset($row['default_quantity']) ? $row['default_quantity'] : $item->default_unit_quantity,
            'buy_price' => isset($row['buy_price']) ? $row['buy_price'] : $item->buy_price,
            'store_id' => isset($row['store_id']) ? $row['store_id'] : $item->store_id
        ]);
    }

    function remove_items_from_balance($deleted_ids = NULL) {
        if ($deleted_ids == NULL) return;
        $items = OpeningBalanceItems::whereIn('id', $deleted_ids)
        ->select('id' ,'opening_balance_id' ,'part_id' ,'quantity' ,'default_unit_quantity' ,'store_id')
        ->get();
        foreach($items as $item) {
            $item->forceDelete();
        }
    }
}
