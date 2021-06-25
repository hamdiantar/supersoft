<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartPrice extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['part_id','unit_id','barcode','selling_price','purchase_price','less_selling_price','service_selling_price'
        ,'less_service_selling_price','maximum_sale_amount','minimum_for_order','biggest_percent_discount', 'biggest_amount_discount',
        'quantity', 'last_selling_price', 'last_purchase_price', 'default_purchase', 'default_sales', 'default_maintenance',
        'supplier_barcode','damage_price'
    ];

    protected $table = 'part_prices';

    public function part () {
        return $this->belongsTo(Part::class, 'part_id')->withTrashed();
    }

    public function unit () {
        return $this->belongsTo(SparePartUnit::class, 'unit_id')->withTrashed();
    }

//    public function priceSegments() {
//
//        return $this->belongsToMany(PriceSegment::class, 'part_price_price_segment')
//            ->withPivot('price', 'sales_price', 'maintenance_price', 'id');
//    }

//    public function getPivotPrice ($id) {
//
//       return $this->priceSegments()->wherePivot('price_segment_id',$id)->first();
//    }

    public function partPriceSegments () {

        return $this->hasMany(PartPriceSegment::class, 'part_price_id');
    }

}
