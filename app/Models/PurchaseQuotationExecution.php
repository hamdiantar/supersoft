<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PurchaseQuotationExecution extends Model
{
    protected $fillable = ['purchase_quotation_id', 'date_from', 'date_to', 'status', 'notes'];

    protected $table = 'purchase_quotation_executions';

    public function purchaseQuotation()
    {
        return $this->belongsTo(PurchaseQuotation::class, 'purchase_quotation_id');
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
