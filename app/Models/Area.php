<?php

namespace App\Models;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Area extends Model
{
    use ColumnTranslation, LogsActivity, SoftDeletes;
    /**
     * table name in db
     *
     * @var String
     */
    protected $table = "areas";

    protected $dates = ['deleted_at'];

    protected static $logAttributes = ['name_ar', 'name_en'];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_ar', 'name_en', 'city_id'
    ];

    public function city()
    {
        return $this->belongsTo(City::class)->withTrashed();
    }
}
