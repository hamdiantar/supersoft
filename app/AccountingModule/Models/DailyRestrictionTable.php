<?php

namespace App\AccountingModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyRestrictionTable extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'daily_restriction_id' ,'accounts_tree_id' ,'debit_amount' ,'credit_amount' ,'notes' ,'account_tree_code',
        'cost_center_root_id' ,'cost_center_id' ,'cost_center_code'
    ];

    function daily_restriction() {
        return $this->belongsTo(DailyRestriction::class ,'daily_restriction_id');
    }

    function account_tree() {
        return $this->belongsTo(AccountsTree::class ,'accounts_tree_id');
    }

    function cost_center_root() {
        return $this->belongsTo(CostCenter::class ,'cost_center_root_id');
    }

    function cost_center() {
        return $this->belongsTo(CostCenter::class ,'cost_center_id');
    }
}
