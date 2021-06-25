<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'supplier_id',
        'bank_name',
        'account_name',
        'branch',
        'account_number',
        'iban',
        'swift_code',
        'customer_id',
    ];

    protected $table = 'bank_accounts';

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withTrashed();
    }

    public function customer()
    {
        return $this->belongsTo(Supplier::class, 'customer_id')->withTrashed();
    }
}
