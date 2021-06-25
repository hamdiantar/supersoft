<?php

namespace App\AccountingModule\Models;

use App\Scopes\OldBranchScope;
use Illuminate\Database\Eloquent\Model;

class ExpenseTypes extends Model
{
    protected $table = "expenses_types";

    protected $fillable = ['type_ar' ,'type_en'];

    function items() {
        return $this->hasMany(ExpenseItems::class ,'expense_id');
    }

    function GetNameAttribute() {
        return app()->getLocale() == 'ar' ? $this->type_ar : $this->type_en;
    }

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new OldBranchScope());
    }
}
