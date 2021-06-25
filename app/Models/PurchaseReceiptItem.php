<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReceiptItem extends Model
{
    protected $fillable = ['purchase_receipt_id', 'part_id', 'part_price_id', 'total_quantity', 'refused_quantity',
        'accepted_quantity', 'defect_percent', 'supply_order_item_id', 'price', 'store_id'];

    protected $table = 'purchase_receipt_items';

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id');
    }

    public function partPrice()
    {
        return $this->belongsTo(PartPrice::class, 'part_price_id');
    }

    public function supplyOrderItem () {
       return $this->belongsTo(SupplyOrderItem::class, 'supply_order_item_id');
    }

    public function getOldAcceptedQuantityAttribute () {

        $oldAcceptedQuantity = $this->supplyOrderItem ? $this->supplyOrderItem->accepted_quantity : 0;

        if (!$oldAcceptedQuantity) {
            return 0;
        }

        return $oldAcceptedQuantity - $this->accepted_quantity;
    }

    public function getRemainingQuantityAttribute () {

        $remainingQuantity = $this->supplyOrderItem ? $this->supplyOrderItem->remaining_quantity_for_accept : 0;

//        if (!$remainingQuantity) {
//            return 0;
//        }

        return $remainingQuantity + $this->accepted_quantity;
    }

    public function getCalculateDefectedPercentAttribute () {

        $refusedQuantity = $this->remaining_quantity - $this->accepted_quantity;

        if ($refusedQuantity < 0) {
            $refusedQuantity = 0;
        }

        return round($refusedQuantity * 100 / $this->total_quantity,2);
    }

    public function getQuantityAttribute () {
        return $this->accepted_quantity;
    }
}
