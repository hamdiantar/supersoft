<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ExpensesItem extends Model
{
    use ColumnTranslation, SoftDeletes, LogsActivity;

    protected $table = 'expenses_items';

    protected $fillable = [
        'item_ar',
        'item_en',
        'status',
        'notes',
        'expense_id',
        'branch_id',
        'is_seeder',
    ];

    protected static $logAttributes = ['item_ar','item_en','is_seeder'];

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

    public function expenseType(): BelongsTo
    {
        return $this->belongsTo(ExpensesType::class, 'expense_id', 'id')->withTrashed();
    }
}
