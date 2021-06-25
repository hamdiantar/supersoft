<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationType extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['quotation_id', 'type'];

    protected $table = 'quotation_types';

    public function items()
    {
        return $this->hasMany(QuotationTypeItem::class, 'quotation_type_id');
    }

    public function winchRequest()
    {

        return $this->hasOne(QuotationWinchRequest::class, 'quotation_type_id');
    }
}
