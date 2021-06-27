<?php

namespace App\Models;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Spatie\Activitylog\Traits\LogsActivity;

class Asset extends Model
{
    use ColumnTranslation, LogsActivity, SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'assets_tb';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'name_ar',
        'name_en',
        'asset_group_id',
        'asset_type_id',
        'asset_status',
        'annual_consumtion_rate',
        'branch_id',
        'asset_details',
        'asset_age',
        'purchase_date',
        'date_of_work',
        'purchase_cost',
        'past_consumtion',
        'current_consumtion',
        'total_current_consumtion',
        'book_value',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static $logAttributes = [
        'name_ar',
        'name_en',
        'asset_group_id',
        'asset_type_id',
        'asset_status',
        'annual_consumtion_rate',
        'branch_id',
        'asset_details',
        'asset_age',
        'purchase_date',
        'date_of_work',
        'purchase_cost',
        'past_consumtion',
        'current_consumtion',
        'total_current_consumtion',
        'book_value',
     ];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }


    function group() {
        return $this->belongsTo(AssetGroup::class ,'asset_group_id');
    }

    function type() {
        return $this->belongsTo(AssetType::class ,'asset_type_id');
    }

    function branch() {
        return $this->belongsTo(Branch::class ,'branch_id');
    }
    public function asset_employees() {
        return $this->hasMany(AssetEmployee::class ,'asset_id');
    }
}
