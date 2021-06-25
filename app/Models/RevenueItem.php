<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class RevenueItem extends Model
{
    use SoftDeletes, ColumnTranslation, LogsActivity;

    protected $table = 'revenue_items';

    protected $fillable = [
        'item_ar',
        'item_en',
        'status',
        'notes',
        'revenue_id',
        'branch_id',
        'is_seeder',
    ];

    protected static $logAttributes = ['item_ar','item_en','is_seeder'];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }

    public function revenueType(): BelongsTo
    {
        return $this->belongsTo(RevenueType::class, 'revenue_id', 'id')->withTrashed();
    }
}
