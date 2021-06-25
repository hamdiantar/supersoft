<?php

namespace App\AccountingModule\Models;

use App\Scopes\OldBranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostCenter extends Model
{
    use SoftDeletes;

    protected$fillable = ['cost_centers_id' ,'tree_level' ,'name_en' ,'name_ar' ,'branch_id'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OldBranchScope());
    }

    function my_branches() {
        return $this->hasMany(CostCenter::class ,'cost_centers_id');
    }

    function my_parent_cost() {
        return $this->belongsTo(static::class ,'cost_centers_id');
    }
}
