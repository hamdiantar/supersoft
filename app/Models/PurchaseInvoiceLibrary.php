<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoiceLibrary extends Model
{
    protected $fillable = ['name', 'purchase_invoice_id', 'file_name', 'extension'];

    protected $table = 'purchase_invoice_libraries';

    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
    }
}
