<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetExpense extends Model
{
    /**
     * @var string
     */
    protected $table = 'asset_expenses';

    /**
     * @var string[]
     */
    protected $fillable = [
        'branch_id',
        'number',
        'dateTime',
        'notes',
        'status',
        'total',
    ];

    public function assetExpensesItems(): HasMany
    {
        return $this->hasMany(AssetExpenseItem::class, 'asset_expense_id');
    }
}
