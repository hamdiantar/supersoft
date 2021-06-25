<?php

namespace App\Models;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class City extends Model
{
    use ColumnTranslation, LogsActivity,SoftDeletes;
    /**
     * table name in db
     *
     * @var String
     */
    protected $table = "cities";

    protected static $logAttributes = ['name_ar', 'name_en'];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_ar','name_en','country_id'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class)->withTrashed();
    }

    public function areas()
    {
        return $this->hasMany(Area::class)->withTrashed();
    }
}
