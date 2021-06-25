<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeRewardDiscount extends Model
{
    protected $table = 'employee_reward_discounts';

    protected $fillable = [
        'type',
        'date',
        'reason',
        'cost',
        'notes',
        'employee_data_id',
        'branch_id',
    ];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(function (\Illuminate\Database\Eloquent\Builder $builder) {
            if (auth()->check() && !authIsSuperAdmin()){
                $builder->where('branch_id', auth()->user()->branch_id);
            }
        });
    }

    public function employeeDate(): BelongsTo
    {
        return $this->belongsTo(EmployeeData::class, 'employee_data_id');
    }

    public function getTypeAttribute(string $value)
    {
        return __($value);
    }
}
