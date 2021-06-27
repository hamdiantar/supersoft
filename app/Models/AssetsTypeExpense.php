<?php

namespace App\Models;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetsTypeExpense extends Model
{
    use ColumnTranslation;
    /**
     * @var string
     */
    protected $table = 'assets_type_expenses';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name_ar',
        'name_en',
        'branch_id',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function assetsItemsExpenses(): HasMany
    {
        return $this->hasMany(AssetsItemExpense::class, 'assets_type_expenses_id');
    }
}
