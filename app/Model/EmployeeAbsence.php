<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Scopes\BranchScope;

class EmployeeAbsence extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id', 'absence_days','absence_type', 'date', 'notes', 'branch_id'
    ];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    function employee() {
        return $this->belongsTo(\App\Models\EmployeeData::class ,'employee_id');
    }

    function branch() {
        return $this->belongsTo(\App\Models\Branch::class ,'branch_id');
    }
}
