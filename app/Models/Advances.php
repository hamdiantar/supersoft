<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advances extends Model
{
    use SoftDeletes;

    protected $table = 'advances';

    protected $fillable = [
        'deportation',
        'operation',
        'date',
        'notes',
        'amount',
        'rest',
        'deportation_id',
        'employee_data_id',
        'branch_id',
        'locker_id',
        'account_id',
        'salary_id'
    ];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(function (\Illuminate\Database\Eloquent\Builder $builder) {
            if (auth()->check() && !authIsSuperAdmin()){
                $builder->where('branch_id', auth()->user()->branch_id);
            }
        });
    }

    public function employee() {
        return $this->belongsTo(EmployeeData::class ,'employee_data_id');
    }

    public function expense()
    {
        return $this->belongsTo(ExpensesReceipt::class);
    }

    public function revenue()
    {
        return $this->belongsTo(RevenueReceipt::class);
    }

    public function getOperationAttribute($value)
    {
        return __($value);
    }

    public function getMyReceipt() {
        if ($this->operation == __('deposit')) {
            $receipt = RevenueReceipt::where('advance_id' ,$this->id)->first();
        } else {
            $receipt = ExpensesReceipt::where('advance_id' ,$this->id)->first();
        }
        return $receipt;
    }
}
