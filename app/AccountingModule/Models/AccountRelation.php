<?php
namespace App\AccountingModule\Models;

use App\Scopes\OldBranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\AccountingModule\Traits\AccountRelationModelTrait;
use App\AccountingModule\Models\AccountRelations\TaxesRelated;
use App\AccountingModule\Models\AccountRelations\ActorsRelated;
use App\AccountingModule\Models\AccountRelations\StoresRelated;
use App\AccountingModule\Models\AccountRelations\DiscountRelated;
use App\AccountingModule\Models\AccountRelations\LockersBanksRelated;
use App\AccountingModule\Models\AccountRelations\StorePermissionRelated;
use App\AccountingModule\Models\AccountRelations\ExpensesRevenuesRelated;
use App\AccountingModule\Models\AccountRelations\MoneyPermissionsRelated;

class AccountRelation extends Model
{
    use SoftDeletes ,AccountRelationModelTrait;

    protected $fillable = [
        'accounts_tree_root_id' ,'accounts_tree_id' ,'related_model_name' ,'branch_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OldBranchScope());
    }

    function account_tree_parent() {
        return $this->belongsTo(AccountsTree::class ,'accounts_tree_id');
    }

    function account_tree_root() {
        return $this->belongsTo(AccountsTree::class ,'accounts_tree_root_id');
    }

    function related_actor() {
        return $this->hasOne(ActorsRelated::class ,'account_relation_id');
    }

    function related_money_permission() {
        return $this->hasOne(MoneyPermissionsRelated::class ,'account_relation_id');
    }

    function related_locker_bank() {
        return $this->hasOne(LockersBanksRelated::class ,'account_relation_id');
    }

    function related_expense_revenue() {
        return $this->hasOne(ExpensesRevenuesRelated::class ,'account_relation_id');
    }

    function related_stores_permission() {
        return $this->hasOne(StorePermissionRelated::class ,'account_relation_id');
    }

    function related_store() {
        return $this->hasOne(StoresRelated::class ,'account_relation_id');
    }

    function related_taxes() {
        return $this->hasOne(TaxesRelated::class ,'account_relation_id');
    }

    function related_discounts() {
        return $this->hasOne(DiscountRelated::class ,'account_relation_id');
    }
}
