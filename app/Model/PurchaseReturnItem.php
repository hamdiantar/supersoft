<?php

namespace App\Model;

use App\Models\Part;
use App\Models\Store;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnItem extends Model
{
    protected $table = 'purchase_return_items';

    protected $fillable = [
        'purchase_returns_id',
        'part_id',
        'store_id',
        'available_qty',
        'purchase_qty',
        'last_purchase_price',
        'purchase_price',
        'discount_type',
        'discount',
        'total_before_discount',
        'total_after_discount',
    ];

    public function part(){
        return $this->belongsTo(Part::class, 'part_id')->withTrashed();
    }

    public function store(){
        return $this->belongsTo(Store::class, 'store_id')->withTrashed();
    }

    public function purchaseReturn () {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_returns_id');
    }
}
