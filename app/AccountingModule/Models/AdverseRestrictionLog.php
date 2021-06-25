<?php

namespace App\AccountingModule\Models;

use App\Scopes\OldBranchScope;
use Illuminate\Database\Eloquent\Model;

class AdverseRestrictionLog extends Model
{
    protected $fillable = ['date_from' ,'date_to' ,'accounts_tree_id' ,'fiscal_year' ,'date' ,'branch_id'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OldBranchScope());
    }
}
