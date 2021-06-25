<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\PurchaseInvoice;
use App\OpeningStockBalance\Models\OpeningBalanceItems;
use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;

class OpeningBalance extends Model
{
    protected $fillable = [
        'branch_id',
        'serial_number',
        'operation_date',
        'operation_time',
        'notes',
        'total_money',
        'purchase_invoice_id',
        'user_id',
    ];

    protected $table = 'opening_balances';

    function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    function items()
    {
        return $this->hasMany(OpeningBalanceItems::class, 'opening_balance_id');
    }

    function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    public function concession()
    {
        return $this->morphOne(Concession::class, 'concessionable');
    }

    public function getNumberAttribute()
    {
        return $this->serial_number;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
