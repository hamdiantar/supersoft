<?php

namespace App\Models;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SparePartUnit extends Model
{
    use ColumnTranslation,SoftDeletes;

    protected $table= 'spare_part_units';

    protected $fillable = [
        'unit_ar',
        'unit_en',
    ];
    protected $dates = ['deleted_at'];

    public function parts()
    {
        return $this->hasMany(Part::class);
    }
}
