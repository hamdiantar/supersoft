<?php

namespace App\Models;

use App\Model\LockerUsers;
use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Locker extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = ['name_en','name_ar','branch_id','status','balance','description','special'];

    protected $table = 'lockers';

    protected $dates = ['deleted_at'];

    protected static $logAttributes = ['name_ar','name_en','status','balance','special'];

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

    public function users(){
        return $this->belongsToMany(User::class, 'lockers_users');
    }

    public function revenueReceipts(){
        return $this->hasMany(RevenueReceipt::class, 'locker_id');
    }

    public function expensesReceipts(){
        return $this->hasMany(ExpensesReceipt::class, 'locker_id');
    }

    public function accessible_users() {
        return $this->hasMany(\App\Model\LockerUsers::class ,'locker_id');
    }

    function get_trans_name() {
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }

    function assigned_users() {
        return $this->hasMany(LockerUsers::class ,'locker_id');
    }

}
