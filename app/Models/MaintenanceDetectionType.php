<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class MaintenanceDetectionType extends Model
{
    use SoftDeletes, LogsActivity,SoftDeletes;

    protected static $logAttributes = ['name_ar','name_en','status'];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }

    protected $dates = ['deleted_at'];

    protected $fillable = ['branch_id','name_ar','name_en','status','description'];

    protected $table = 'maintenance_detection_types';

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

    public function maintenance(){
        return $this->hasMany(MaintenanceDetection::class, 'maintenance_type_id');
    }
}
