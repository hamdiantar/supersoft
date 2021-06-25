<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Quotation extends Model
{
    use SoftDeletes, LogsActivity;

    protected $dates = ['deleted_at'];

    protected $fillable = ['quotation_number','customer_id','branch_id','created_by','date','time','discount_type',
        'discount','tax','sub_total','total_after_discount','total','status','rejected_reason'];

    protected $table = 'quotations';

    protected static $logAttributes = ['invoice_number', 'date','time','total'];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id')->withTrashed();
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function types(){
        return $this->hasMany(QuotationType::class, 'quotation_id');
    }

    public function getQuoNumberAttribute(){
        return  '##_'.$this->quotation_number;
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }
}
