<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class SupplyOrderExecution extends Model
{
    protected $fillable = ['supply_order_id', 'date_from', 'date_to', 'status', 'notes'];

    protected $table = 'supply_order_executions';

    public function supplyOrder()
    {
        return $this->belongsTo(SupplyOrder::class, 'supply_order_id');
    }

    public function getStartDateAttribute()
    {
        return Carbon::create($this->date_from)->format('Y-m-d');
    }

    public function getEndDateAttribute()
    {
        return Carbon::create($this->date_to)->format('Y-m-d');
    }
}
