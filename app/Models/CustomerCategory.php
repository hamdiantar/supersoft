<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class CustomerCategory extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = ['name_ar','name_en','branch_id','status','sales_discount_type','sales_discount',
        'services_discount_type','services_discount','description'];

    protected $table = 'customer_categories';

    protected static $logAttributes = ['name_ar','name_en','status','sales_discount','services_discount'];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function getActiveAttribute(){
        return $this->status == 1? __('Active'): __('inActive');
    }

    public function getNameAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['name_ar'] : $this['name_en'];
    }
}
