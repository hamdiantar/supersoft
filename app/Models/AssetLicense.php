<?php

namespace App\Models;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Spatie\Activitylog\Traits\LogsActivity;

class AssetLicense extends Model
{
    use ColumnTranslation, LogsActivity, SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'assets_licenses';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'license_details',
        'start_date',
        'end_date',
        'asset_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected static $logAttributes = [
        'license_details',
        'start_date',
        'end_date',
        'asset_id',
    ];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    

    
}
