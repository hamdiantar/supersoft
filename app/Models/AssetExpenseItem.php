<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetExpenseItem extends Model
{
    /**
     * @var string
     */
    protected $table = 'asset_expense_items';

    /**
     * @var string[]
     */
    protected $fillable = [
        'price',
        'asset_id',
        'asset_expense_id',
        'asset_expense_item_id',
    ];

    public function assetExpenseItem(): BelongsTo
    {
        return $this->belongsTo(AssetsItemExpense::class, 'asset_expense_item_id');
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
