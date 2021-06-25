<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartPriceSegment extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['part_price_id','name','purchase_price','sales_price', 'maintenance_price'];

    protected $table = 'part_price_segments';

    public function partPrice () {

        return $this->belongsTo(PartPrice::class, 'part_price_id')->withTrashed();
    }
}
