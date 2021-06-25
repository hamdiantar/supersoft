<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseQuotationLibrary extends Model
{
    protected $fillable = ['name', 'purchase_quotation_id', 'file_name', 'extension'];

    protected $table = 'purchase_quotation_libraries';

    public function purchaseQuotation()
    {
        return $this->belongsTo(PurchaseQuotation::class, 'purchase_quotation_id');
    }
}
