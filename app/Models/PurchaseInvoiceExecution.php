<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PurchaseInvoiceExecution extends Model
{
    protected $fillable = ['purchase_invoice_id', 'date_from', 'date_to', 'status', 'notes'];

    protected $table = 'purchase_invoice_executions';

    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
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
