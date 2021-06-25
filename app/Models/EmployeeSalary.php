<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Scopes\BranchScope;

class EmployeeSalary extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'branch_id', 'employee_id', 'date_from', 'date_to', 'salary', 'insurances', 'allowances', 'advance_included',
        'date', 'deportation_method', 'deportation_id', 'employee_data', 'locker_id', 'account_id', 'pay_type',
        'paid_amount', 'rest_amount', 'cost_center_id'
    ];

    public $casts = [
        'employee_data' => 'array'
    ];

    function employee() {
        return $this->belongsTo(EmployeeData::class ,'employee_id');
    }

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    function payments() {
        return $this->hasMany(ExpensesReceipt::class ,'employee_salary_id');
    }
}
