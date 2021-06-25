<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Settlement extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['branch_id', 'user_id', 'number', 'date', 'time', 'total', 'description', 'type'];

    protected $table = 'settlements';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getSpecialNumberAttribute()
    {
        return $this->number . '_#';
    }

    public function items()
    {
        return $this->hasMany(SettlementItem::class, 'settlement_id');
    }

    public function concession()
    {
        return $this->morphOne(Concession::class, 'concessionable');
    }
}
