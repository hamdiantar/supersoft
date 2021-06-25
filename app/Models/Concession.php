<?php

namespace App\Models;

use App\OpeningStockBalance\Models\OpeningBalance;
use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Concession extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['branch_id', 'number', 'date', 'user_id', 'status', 'time', 'type', 'concession_type_id', 'concessionable_id',
        'concessionable_type', 'total_quantity', 'description', 'library_path'];

    protected $table = 'concessions';

    public function concessionable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function concessionType()
    {
        return $this->belongsTo(ConcessionType::class, 'concession_type_id');
    }

    public function concessionItems()
    {
        return $this->hasMany(ConcessionItem::class, 'concession_id');
    }

//    public function openingBalance () {
//
//        return $this->belongsTo(OpeningBalance::class, 'concessionable_id');
//    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function getAddNumberAttribute()
    {
        return $this->number;
    }

    public function getWithdrawalNumberAttribute()
    {
        return $this->number;
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    public function concessionExecution()
    {
        return $this->hasOne(ConcessionExecution::class, 'concession_id');
    }

    public function execution()
    {
        return $this->hasOne(ConcessionExecution::class, 'concession_id');
    }

    function files()
    {
        return $this->hasMany(ConcessionLibrary::class, 'concession_id');
    }

    public function getTotalAttribute () {

        $total = 0;

        foreach ($this->concessionItems as $item){

            $total += $item->price * $item->quantity;
        }

        return $total;
    }
}
