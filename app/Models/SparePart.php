<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class SparePart extends Model
{
    use ColumnTranslation, LogsActivity,SoftDeletes;

    protected $table = 'spare_parts';

    protected $fillable = [
        'type_ar',
        'type_en',
        'branch_id',
        'status',
        'spare_part_id'
    ];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }

    public function parts(){
        return $this->hasMany(Part::class, 'spare_part_type_id');
    }

    public function allParts(){
        return $this->belongsToMany(Part::class, 'part_spare_part', 'spare_part_type_id', 'part_id');
    }

    public function getTypeAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['type_ar'] : $this['type_en'];
    }

    public function parent(){

        return $this->belongsTo(SparePart::class , 'spare_part_id');
    }

    public function children(){

        return $this->hasMany(SparePart::class , 'spare_part_id');
    }

    public function getImgAttribute () {

       return $this->image ? url('storage/images/spare-parts/' . $this->image) : url('default-images/defualt.png');
    }

    public function branch() {
        return $this->belongsTo(Branch::class , 'branch_id');
    }
}
