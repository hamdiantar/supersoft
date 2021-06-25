<?php

namespace App\AccountingModule\Models\AccountRelations;

use App\Models\ExpensesItem;
use App\Models\ExpensesType;
use App\Models\RevenueItem;
use App\Models\RevenueType;
use Illuminate\Database\Eloquent\Model;

class ExpensesRevenuesRelated extends Model
{
    protected $fillable = [
        'account_relation_id' ,'related_as' ,'type_id' ,'item_id'
    ];

    function expense_type() {
        return $this->belongsTo(ExpensesType::class ,'type_id');
    }

    function expense_item() {
        return $this->belongsTo(ExpensesItem::class ,'item_id');
    }

    function revenue_type() {
        return $this->belongsTo(RevenueType::class ,'type_id');
    }

    function revenue_item() {
        return $this->belongsTo(RevenueItem::class ,'item_id');
    }
}
