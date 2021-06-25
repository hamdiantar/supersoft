<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseQuotationItem extends Model
{
    protected $fillable = ['purchase_quotation_id', 'part_id', 'part_price_id', 'quantity', 'price', 'sub_total', 'discount',
        'discount_type', 'total_after_discount', 'tax', 'total', 'active', 'part_price_segment_id'];


    protected $table = 'purchase_quotation_items';

    public function purchaseQuotation()
    {
        return $this->belongsTo(PurchaseQuotation::class, 'purchase_quotation_id');
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
        return $this->belongsToMany(TaxesFees::class, 'purchase_quotation_item_taxes_fees', 'item_id', 'tax_id');
    }

    public function partPriceSegment()
    {
        return $this->belongsTo(PartPriceSegment::class, 'part_price_segment_id');
    }

    public function spareParts()
    {
        return $this->belongsToMany(SparePart::class, 'purchase_quotation_items_spare_parts', 'item_id', 'spare_part_id')
            ->withPivot('price');
    }
}
