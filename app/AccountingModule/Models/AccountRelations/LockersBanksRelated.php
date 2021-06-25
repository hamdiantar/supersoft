<?php

namespace App\AccountingModule\Models\AccountRelations;

use App\Models\Account;
use App\Models\Locker;
use Illuminate\Database\Eloquent\Model;

class LockersBanksRelated extends Model
{
    /**
    related_as : locker ,bank
    related_id : we use this column for custom locker or bank
     */
    protected $fillable = [
        'account_relation_id' ,'related_as' ,'related_id'
    ];

    function locker() {
        return $this->belongsto(Locker::class ,'related_id');
    }

    function bank() {
        return $this->belongsto(Account::class ,'related_id');
    }
}
