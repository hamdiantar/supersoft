<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class SupplierGroup extends Model
{
    use SoftDeletes, LogsActivity;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    protected static $logAttributes = [
        'name_ar',
        'name_en',
        'discount',
        'discount_type',
        'supplier_group_id',
    ];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $fillable = ['name_en', 'name_ar', 'branch_id', 'status', 'discount_type', 'discount', 'description',  'supplier_group_id'];

    protected $table = 'supplier_groups';

    protected $dates = ['deleted_at'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function getNameAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['name_ar'] : $this['name_en'];
    }

    public function getActiveAttribute()
    {
        return $this->status == 1 ? __('Active') : __('inActive');
    }

    public function children(): HasMany
    {
        return $this->hasMany(SupplierGroup::class, 'supplier_group_id');
    }
}
