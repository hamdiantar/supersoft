<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class ConcessionType extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['branch_id', 'name_ar', 'name_en', 'type', 'description', 'concession_type_item_id'];

    protected $table = 'concession_types';

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function getNameAttribute()
    {

        return App::getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }

    public function concessionItem () {

        return $this->belongsTo(ConcessionTypeItem::class, 'concession_type_item_id');
    }

    public function concessions () {

        return $this->hasMany(Concession::class, 'concession_type_id');
    }
}
