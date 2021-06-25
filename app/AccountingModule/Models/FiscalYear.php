<?php

namespace App\AccountingModule\Models;

use App\Scopes\OldBranchScope;
use Illuminate\Database\Eloquent\Model;

class FiscalYear extends Model
{
    protected $fillable = ['name_ar' ,'name_en' ,'start_date' ,'end_date' ,'status' ,'branch_id'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OldBranchScope());
    }

    function daily_restrictions() {
        return $this->hasMany(DailyRestriction::class ,'fiscal_year_id');
    }
}
