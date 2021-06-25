<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TaxesFees extends Model
{
    use LogsActivity,SoftDeletes;

    protected $table = 'taxes_fees';

    protected $fillable = [
        'name_ar',
        'name_en',
        'active_services',
        'active_invoices',
        'active_offers',
        'branch_id',
        'value',
        'tax_type',
        'active_purchase_invoice',
        'type',
        'on_parts',
        'purchase_quotation',
        'execution_time'
    ];

    public function getNameAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['name_ar'] : $this['name_en'];
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }
}
