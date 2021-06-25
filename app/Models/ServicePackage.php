<?php

namespace App\Models;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePackage extends Model
{
    use ColumnTranslation, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'service_packages';

    /**
     * @var array
     */
    protected $fillable = [
        'name_ar',
        'name_en',
        'total_before_discount',
        'total_after_discount',
        'services_number',
        'discount_type',
        'discount_value',
        'branch_id',
        'service_id',
        'number_of_hours',
        'number_of_min',
        'q',
    ];

    public function setServiceIdAttribute($value)
    {
        $this->attributes['service_id'] = serialize($value);
    }

    public function setQAttribute($value)
    {
        $this->attributes['q'] = serialize($value);
    }

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }
}
