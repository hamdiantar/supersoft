<?php

namespace App\AccountingModule\Models\AccountRelations;

use App\Models\TaxesFees;
use Illuminate\Database\Eloquent\Model;

class TaxesRelated extends Model
{
    protected $fillable = [
        'account_relation_id' ,'tax_id'
    ];

    function tax() {
        return $this->belongsTo(TaxesFees::class ,'tax_id');
    }
}
