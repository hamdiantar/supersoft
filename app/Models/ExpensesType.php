<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ExpensesType extends Model
{
    use ColumnTranslation, SoftDeletes, LogsActivity;

    protected $table = 'expenses_types';

    protected $fillable = [
        'type_en',
        'type_ar',
        'status',
        'branch_id',
        'is_seeder',
    ];

    protected static $logAttributes = ['type_en','type_ar','is_seeder'];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }

    public function revenueItems(): HasMany
    {
        return $this->hasMany(ExpensesItem::class, 'expense_id', 'id')->withTrashed();;
    }
}
