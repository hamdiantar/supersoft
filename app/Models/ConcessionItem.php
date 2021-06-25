<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConcessionItem extends Model
{
    protected $fillable = ['concession_id', 'part_id', 'part_price_id', 'store_id', 'quantity', 'price',
        'part_price_segment_id', 'accepted_status', 'log_message'];

    protected $table = 'concession_items';

    public function concession()
    {
        return $this->belongsTo(Concession::class, 'concession_id')->withTrashed();
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id')->withTrashed();
    }

    public function partPrice()
    {
        return $this->belongsTo(PartPrice::class, 'part_price_id')->withTrashed();
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id')->withTrashed();
    }

    public function partPriceSegment()
    {
        return $this->belongsTo(PartPriceSegment::class, 'part_price_segment_id')->withTrashed();
    }
}
