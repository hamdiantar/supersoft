<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettlementItem extends Model
{
    protected $fillable = ['settlement_id','part_id','store_id','part_price_id','part_price_segment_id','quantity', 'price'];

    protected $table = 'settlement_items';

    public function settlement () {

        return $this->belongsTo(Settlement::class, 'settlement_id')->withTrashed();
    }

    public function store () {

        return $this->belongsTo(Store::class, 'store_id')->withTrashed();
    }

    public function part () {

        return $this->belongsTo(Part::class, 'part_id')->withTrashed();
    }

    public function partPrice () {

        return $this->belongsTo(PartPrice::class, 'part_price_id')->withTrashed();
    }

    public function partPriceSegment () {

        return $this->belongsTo(PartPriceSegment::class, 'part_price_segment_id')->withTrashed();
    }
}
