<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointLog extends Model
{
    protected $fillable = ['branch_id', 'sales_invoice_id', 'sales_invoice_return_id', 'card_invoice_id', 'customer_id', 'points',
        'log', 'type', 'amount', 'setting_amount', 'setting_points'];

    protected $table = 'point_logs';

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }

    public function cardInvoice()
    {
        return $this->belongsTo(CardInvoice::class, 'card_invoice_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id')->withTrashed();
    }
}
