<?php

namespace App\AccountingModule\Models\AccountRelations;

use Illuminate\Database\Eloquent\Model;

class DiscountRelated extends Model
{
    // discount_type : earned ,permitted ,points
    protected $fillable = [
        'account_relation_id' ,'discount_type'
    ];
}
