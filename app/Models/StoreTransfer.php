<?php
namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreTransfer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transfer_number' ,'transfer_date' ,'store_from_id' ,'store_to_id' ,'branch_id','total','time','user_id', 'description'
    ];

    function items() {
        return $this->hasMany(StoreTransferItem::class ,'store_transfer_id');
    }

    function store_from() {
        return $this->belongsTo(Store::class ,'store_from_id');
    }

    function store_to() {
        return $this->belongsTo(Store::class ,'store_to_id');
    }

    function branch() {
        return $this->belongsTo(Branch::class ,'branch_id');
    }

    public function user () {

        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function getNumberAttribute () {

        return $this->transfer_number . '_#';
    }

    public function concession()
    {
        return $this->morphOne(Concession::class, 'concessionable');
    }

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }
}
