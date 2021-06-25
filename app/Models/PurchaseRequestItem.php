<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequestItem extends Model
{
    use SoftDeletes;

    protected $fillable = ['purchase_request_id', 'part_id', 'part_price_id', 'quantity', 'approval_quantity'];

    protected $table = 'purchase_request_items';

    protected $dates = ['deleted_at'];

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id')->withTrashed();
    }

    public function partPrice()
    {
        return $this->belongsTo(PartPrice::class, 'part_price_id')->withTrashed();
    }

    public function spareParts () {

        return $this->belongsToMany(SparePart::class, 'purchase_request_items_spare_parts');
    }
}
