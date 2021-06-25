<?php

namespace App\AccountingModule\Models;

use App\Scopes\OldBranchScope;
use Illuminate\Database\Eloquent\Model;

class ExpenseItems extends Model
{
    protected $table = "expenses_items";
    
    protected $fillable = ['item_ar' ,'item_en' ,'expense_id'];

    function type() {
        return $this->belongsTo(ExpenseTypes::class ,'expense_id');
    }

    function GetNameAttribute() {
        return app()->getLocale() == 'ar' ? $this->item_ar : $this->item_en;
    }

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new OldBranchScope());
    }
}
