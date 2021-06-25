<?php

namespace App\Models;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeSetting extends Model
{
    use ColumnTranslation;

    protected $table = 'employee_settings';

    protected $fillable = [
        'name_ar',
        'name_en',
        'time_attend',
        'time_leave',
        'daily_working_hours',
        'annual_vocation_days',
        'max_advance',
        'amount_account',
        'status',
        'type_account',
        'type_absence',
        'type_absence_equal',
        'hourly_extra',
        'hourly_extra_equal',
        'hourly_delay',
        'hourly_delay_equal',
        'saturday',
        'sunday',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'branch_id',
        'shift_id',
        'card_work_percent',
        'service_status'
    ];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(function (\Illuminate\Database\Eloquent\Builder $builder) {
            if (auth()->check() && !authIsSuperAdmin()){
                $builder->where('branch_id', auth()->user()->branch_id);
            }
        });
    }

    public function getTypeAccountAttribute(string $value)
    {
        return __($value);
    }
}
