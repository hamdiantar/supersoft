<?php

namespace App\AccountingModule\Models;

use App\Scopes\OldBranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyRestriction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'restriction_number', 'operation_number', 'operation_date', 'debit_amount', 'reference_type',
        'credit_amount', 'records_number', 'auto_generated', 'fiscal_year_id', 'is_adverse', 'reference_id' ,'branch_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OldBranchScope());
    }

    function my_table() {
        return $this->hasMany(DailyRestrictionTable::class ,'daily_restriction_id');
    }

    function am_deleteable() {
        if (user_can_access_accounting_module(NULL ,'daily-restrictions' ,'delete')) {
            if ($this->auto_generated == 0) return true;
        }
        return false;
    }

    function am_editable() {
        if (user_can_access_accounting_module(NULL ,'daily-restrictions' ,'edit')) {
            if ($this->auto_generated == 0) return true;
        }
        return false;
    }

    function am_printable() {
        if (user_can_access_accounting_module(NULL ,'daily-restrictions' ,'print')) return true;
        return false;
    }

    public function fiscal_year() {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year_id');
    }
}
