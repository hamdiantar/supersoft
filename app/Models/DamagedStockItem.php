<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DamagedStockItem extends Model
{
    protected $fillable = ['damaged_stock_id', 'part_id', 'store_id', 'part_price_id', 'part_price_segment_id', 'quantity', 'price'];

    protected $table = 'damaged_stock_items';

    public function damagedStock()
    {
        return $this->belongsTo(DamagedStock::class, 'damaged_stock_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id')->withTrashed();
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id')->withTrashed();
    }

    public function partPrice()
    {
        return $this->belongsTo(PartPrice::class, 'part_price_id')->withTrashed();
    }

}
