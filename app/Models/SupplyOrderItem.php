<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyOrderItem extends Model
{
    protected $fillable = ['supply_order_id', 'part_id', 'part_price_id', 'quantity', 'price', 'sub_total', 'discount',
        'discount_type', 'total_after_discount', 'tax', 'total', 'active', 'part_price_segment_id'];

    protected $table = 'supply_order_items';

    public function supplyOrder()
    {
        return $this->belongsTo(SupplyOrder::class, 'supply_order_id');
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id');
    }

    public function partPrice()
    {
        return $this->belongsTo(PartPrice::class, 'part_price_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(TaxesFees::class, 'supply_order_item_taxes_fees', 'item_id', 'tax_id');
    }

    public function partPriceSegment()
    {
        return $this->belongsTo(PartPriceSegment::class, 'part_price_segment_id');
    }

    public function getRemainingQuantityForAcceptAttribute () {

        $acceptedQuantity = PurchaseReceiptItem::where('supply_order_item_id', $this->id)->sum('accepted_quantity');
        return $this->quantity - $acceptedQuantity;
    }

    public function getAcceptedQuantityAttribute () {
        return PurchaseReceiptItem::where('supply_order_item_id', $this->id)->sum('accepted_quantity');
    }
}
