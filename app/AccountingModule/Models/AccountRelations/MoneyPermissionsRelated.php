<?php

namespace App\AccountingModule\Models\AccountRelations;

use Illuminate\Database\Eloquent\Model;

class MoneyPermissionsRelated extends Model
{
    /**
    money_gateway : locker ,bank
    act_as : exchange ,receive
     */
    protected $fillable = [
        'account_relation_id' ,'money_gateway' ,'act_as'
    ];
}
