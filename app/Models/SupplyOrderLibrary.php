<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyOrderLibrary extends Model
{
    protected $fillable = ['name', 'supply_order_id', 'file_name', 'extension'];

    protected $table = 'supply_order_libraries';

    public function supplyOrder()
    {
        return $this->belongsTo(SupplyOrder::class, 'supply_order_id');
    }
}
