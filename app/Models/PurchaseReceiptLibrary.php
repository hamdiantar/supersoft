<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReceiptLibrary extends Model
{
    protected $fillable = ['name', 'purchase_receipt_id', 'file_name', 'extension'];

    protected $table = 'purchase_receipt_libraries';

    public function purchaseReceipt()
    {
        return $this->belongsTo(PurchaseReceipt::class, 'purchase_receipt_id');
    }
}
