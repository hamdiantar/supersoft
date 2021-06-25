<?php

namespace App\Models;

use App\ModelsMoneyPermissions\BankTransferPivot;
use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class AccountTransfer extends Model
{
    use SoftDeletes, LogsActivity;

    protected static $logAttributes = ['amount','date','created_by'];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $fillable = ['branch_id','account_from_id','account_to_id','created_by','date','amount','description'];

    protected $table = 'account_transfers';

    protected $dates = ['deleted_at'];

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

    public function accountFrom(){
        return $this->belongsTo(Account::class,'account_from_id')->withTrashed();
    }

    public function accountTo(){
        return $this->belongsTo(Account::class,'account_to_id')->withTrashed();
    }

    function bank_transfer_pivot() {
        return $this->hasOne(BankTransferPivot::class ,'bank_transfer_id');
    }
}
