<?php

namespace App\AccountingModule\Models;

use App\Scopes\OldBranchScope;
use Illuminate\Database\Eloquent\Model;

class RevenueTypes extends Model
{
    protected $table = "revenue_types";

    protected $fillable = ['type_ar' ,'type_en'];

    function items() {
        return $this->hasMany(RevenueItems::class ,'revenue_id');
    }

    function GetNameAttribute() {
        return app()->getLocale() == 'ar' ? $this->type_ar : $this->type_en;
    }

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new OldBranchScope());
    }
}
