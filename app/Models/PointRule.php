<?php

namespace App\Models;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PointRule extends Model
{
    use SoftDeletes, ColumnTranslation;

    protected $dates = ['deleted_at'];

    protected $fillable = ['branch_id','points','amount','status','text_ar','text_en'];

    protected $table = 'point_rules';

    public function getTextAttribute(){

        return app()->getLocale() == 'ar' ? $this->text_ar : $this->text_en;
    }

    public function branch () {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function scopeBranch($query)
    {
        if(!authIsSuperAdmin()){
            return $query->where('branch_id', auth()->user()->branch_id);
        }
    }
}
