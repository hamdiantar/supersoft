<?php

namespace App\Models;

use App\Model\AccountUsers;
use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = ['name_en','name_ar','branch_id','status','balance','description','special','number'];

    protected $table = 'accounts';

    protected $dates = ['deleted_at'];

    protected static $logAttributes = ['name_ar','name_en','balance','special','number'];

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
        return $this->belongsToMany(User::class, 'accounts_users');
    }

    public function revenueReceipts(){
        return $this->hasMany(RevenueReceipt::class, 'account_id');
    }

    public function expensesReceipts(){
        return $this->hasMany(ExpensesReceipt::class, 'account_id');
    }

    public function accessible_users() {
        return $this->hasMany(\App\Model\AccountUsers::class ,'account_id');
    }

    function get_trans_name() {
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }

    function assigned_users() {
        return $this->hasMany(AccountUsers::class ,'account_id');
    }
}
