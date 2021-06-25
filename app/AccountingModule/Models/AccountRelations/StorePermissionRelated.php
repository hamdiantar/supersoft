<?php

namespace App\AccountingModule\Models\AccountRelations;

use Illuminate\Database\Eloquent\Model;

class StorePermissionRelated extends Model
{
    protected $fillable = [
        'account_relation_id' ,'permission_nature'
    ];
}
