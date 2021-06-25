<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointSetting extends Model
{
    protected $fillable = ['branch_id','points','amount'];

    protected $table = 'point_settings';

}
