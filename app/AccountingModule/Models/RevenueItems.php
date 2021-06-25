<?php

namespace App\AccountingModule\Models;

use App\Scopes\OldBranchScope;
use Illuminate\Database\Eloquent\Model;

class RevenueItems extends Model
{
    protected $table = "revenue_items";

    protected $fillable = ['item_en' ,'item_ar' ,'revenue_id'];

    function type() {
        return $this->belongsTo(RevenueTypes::class ,'revenue_id');
    }

    function GetNameAttribute() {
        return app()->getLocale() == 'ar' ? $this->item_ar : $this->item_en;
    }

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new OldBranchScope());
    }
}
