<?php

namespace App\Models;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Spatie\Activitylog\Traits\LogsActivity;

class AssetType extends Model
{
    use ColumnTranslation, LogsActivity, SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'assets_types';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'name_ar',
        'name_en',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected static $logAttributes = ['name_ar', 'name_en' ];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }


    public function assets(){
        return $this->hasMany(Asset::class , 'asset_type_id');
    }
    
}
