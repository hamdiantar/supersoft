<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Shift extends Model
{
    use LogsActivity, SoftDeletes, ColumnTranslation;

    /**
     * table name in db
     *
     * @var String
     */
    protected $table = "shifts";

    protected $fillable = [
        'name_ar',
        'name_en',
        'start_from',
        'end_from',
        'Saturday',
        'sunday',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'branch_id',
        'shift_is_active',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }

    protected $dates = ['deleted_at'];
}
