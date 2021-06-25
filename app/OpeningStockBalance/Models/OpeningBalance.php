<?php

namespace App\OpeningStockBalance\Models;

use App\Models\Branch;
use App\Models\PurchaseInvoice;
use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;

class OpeningBalance extends Model
{
    protected $fillable = [
        'branch_id' ,'serial_number' ,'operation_date' ,'operation_time' ,'notes' ,'total_money' ,'purchase_invoice_id'
    ];

    function branch() {
        return $this->belongsTo(Branch::class ,'branch_id');
    }

    function items() {
        return $this->hasMany(OpeningBalanceItems::class ,'opening_balance_id');
    }

    function purchaseInvoice() {
        return $this->belongsTo(PurchaseInvoice::class ,'purchase_invoice_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }
}
