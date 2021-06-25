<?php

namespace App\Models;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Spatie\Activitylog\Traits\LogsActivity;

class AssetEmployee extends Model
{
    use ColumnTranslation, LogsActivity, SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'assets_employees';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'employee_name',
        'phone',
        'start_date',
        'end_date',
        'asset_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected static $logAttributes = [
        'employee_name',
        'phone',
        'start_date',
        'end_date',
    ];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    function asset() {
        return $this->belongsTo(Asset::class ,'asset_id');
    }

    
}
