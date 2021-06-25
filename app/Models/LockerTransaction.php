<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class LockerTransaction extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = ['locker_id','account_id','created_by','branch_id','date','type','amount'];

    protected $table = 'locker_transactions';

    protected $dates = ['deleted_at'];

    protected static $logAttributes = ['date','amount','locker_id','created_by','amount','type'];

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

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function locker(){
        return $this->belongsTo(Locker::class, 'locker_id')->withTrashed();
    }

    public function account(){
        return $this->belongsTo(Account::class, 'account_id')->withTrashed();
    }
}
