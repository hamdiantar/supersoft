<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
