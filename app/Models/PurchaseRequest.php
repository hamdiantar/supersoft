<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequest extends Model
{
    use SoftDeletes;

    protected $fillable = ['number', 'date', 'time', 'branch_id', 'user_id', 'status', 'type', 'request_for', 'requesting_party',
        'date_from', 'date_to', 'description', 'library_path'];

    protected $table = 'purchase_requests';

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function items()
    {
        return $this->hasMany(PurchaseRequestItem::class, 'purchase_request_id');
    }

    public function getSpecialNumberAttribute () {

        return $this->number . '_#';
    }

    public function execution () {

        return $this->hasOne(PurchaseRequestExecution::class, 'purchase_request_id');
    }

    function files() {

        return $this->hasMany(PurchaseRequestLibrary::class ,'purchase_request_id');
    }
}
