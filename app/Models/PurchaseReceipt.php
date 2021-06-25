<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReceipt extends Model
{
    protected $fillable = ['number', 'branch_id', 'user_id', 'supply_order_id', 'date', 'time', 'supplier_id', 'library_path', 'notes'];

    protected $table = 'purchase_receipts';

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function supplyOrder()
    {
        return $this->belongsTo(SupplyOrder::class, 'supply_order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function execution()
    {
        return $this->hasOne(PurchaseReceiptExecution::class, 'purchase_receipt_id');
    }

    public function files()
    {
        return $this->hasMany(PurchaseReceiptLibrary::class, 'purchase_receipt_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseReceiptItem::class, 'purchase_receipt_id');
    }

    public function concession()
    {
        return $this->morphOne(Concession::class, 'concessionable');
    }
}
