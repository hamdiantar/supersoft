<?php

namespace App\OpeningStockBalance\Models;

use App\Models\Part;
use App\Models\PartPrice;
use App\Models\PartPriceSegment;
use App\Models\Store;
use Illuminate\Database\Eloquent\Model;
use App\Models\OpeningBalance;

class OpeningBalanceItems extends Model
{
    protected $fillable = [
        'opening_balance_id' ,'part_id' ,'part_price_id' ,'part_price_price_segment_id' ,
        'quantity' ,'default_unit_quantity', 'buy_price' ,'store_id'
    ];

    function opening_balance() {
        return $this->belongsTo(OpeningBalance::class ,'opening_balance_id');
    }

    function part() {
        return $this->belongsTo(Part::class ,'part_id')->withTrashed();
    }

    function part_price() {
        return $this->belongsTo(PartPrice::class ,'part_price_id')->withTrashed();
    }

    function partPrice() {
        return $this->belongsTo(PartPrice::class ,'part_price_id')->withTrashed();
    }

    function store() {
        return $this->belongsTo(Store::class ,'store_id')->withTrashed();
    }

    function partPriceSegment() {

        return $this->belongsTo(PartPriceSegment::class ,'part_price_price_segment_id')->withTrashed();
    }

    public function getPriceAttribute () {

        return $this->buy_price;
    }

    public function getPartPriceSegmentIdAttribute()
    {
        return $this->part_price_price_segment_id;
    }
}
