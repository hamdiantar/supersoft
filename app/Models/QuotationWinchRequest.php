<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationWinchRequest extends Model
{
    protected $fillable =
        [
            'quotation_type_id',
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

    protected $table = 'quotation_winch_requests';
}
