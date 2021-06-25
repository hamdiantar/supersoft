<?php

namespace App\AccountingModule\Models\AccountRelations;

use App\Models\Branch;
use App\Models\Store;
use Illuminate\Database\Eloquent\Model;

class StoresRelated extends Model
{
    /**
     * related_as : store ,branch-stores
     * related_id : can be the store id or branch id depend on related_as value
     */
    protected $fillable = [
        'account_relation_id' ,'related_as' ,'related_id'
    ];

    function store() {
        return $this->belongsTo(Store::class ,'related_id');
    }

    function stores_branch() {
        return $this->belongsTo(Branch::class ,'related_id');
    }
}
