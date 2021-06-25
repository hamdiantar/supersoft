<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardInvoiceWinchRequest extends Model
{
    protected $table = 'card_invoice_winch_requests';

    protected $fillable =
        [
            'card_invoice_type_id',
            'branch_lat',
            'branch_long',
            'request_lat',
            'request_long',
            'distance',
            'price',
            'sub_total',
            'discount_type',
            'discount',
            'total'
        ];
}
