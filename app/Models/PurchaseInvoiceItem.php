<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseInvoiceItem extends Model
{

    protected $fillable = [
        'purchase_invoice_id',
        'part_id',
        'store_id',
        'available_qty',
        'purchase_qty',
        'last_purchase_price',
        'purchase_price',
        'discount_type',
        'discount',
        'total_after_discount',
        'total_before_discount',
        'subtotal',
        'quantity',
        'part_price_id',
        'part_price_segment_id',
        'tax'
    ];

    protected $table = 'purchase_invoice_items';

    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id')->withTrashed();
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id')->withTrashed();
    }

    public function invoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(TaxesFees::class, 'purchase_invoice_items_taxes_fees');
    }

    public function partPrice()
    {
        return $this->belongsTo(PartPrice::class, 'part_price_id')->withTrashed();
    }

    public function partPriceSegment()
    {
        return $this->belongsTo(PartPriceSegment::class, 'part_price_segment_id');
    }

    public function getPriceAttribute()
    {
        return $this->purchase_price;
    }
}
