<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PurchaseReceiptExecution extends Model
{
    protected $fillable = ['purchase_receipt_id', 'date_from', 'date_to', 'status', 'notes'];

    protected $table = 'purchase_receipt_executions';

    public function purchaseReceipt()
    {
        return $this->belongsTo(PurchaseReceipt::class, 'purchase_receipt_id');
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
