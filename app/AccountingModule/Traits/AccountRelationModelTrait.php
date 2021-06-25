<?php
namespace App\AccountingModule\Traits;

use Exception;

trait AccountRelationModelTrait {
    function GetCustomNatureAttribute() {
        try {
            switch ($this->related_model_name) {
                case '\App\AccountingModule\Models\AccountRelations\LockersBanksRelated':
                    return __('accounting-module.account-relation.lockers-banks');
                    break;
                case '\App\AccountingModule\Models\AccountRelations\MoneyPermissionsRelated':
                    return __('accounting-module.account-relation.money-permissions');
                    break;
                case '\App\AccountingModule\Models\AccountRelations\StoresRelated':
                    return __('accounting-module.account-relation.stores');
                    break;
                case '\App\AccountingModule\Models\AccountRelations\StorePermissionRelated':
                    return __('accounting-module.account-relation.stores-permissions');
                    break;
                case '\App\AccountingModule\Models\AccountRelations\ExpensesRevenuesRelated':
                    return __('accounting-module.account-relation.types-items');
                    break;
                case '\App\AccountingModule\Models\AccountRelations\TaxesRelated':
                    return __('accounting-module.account-relation.taxes');
                    break;
                case '\App\AccountingModule\Models\AccountRelations\DiscountRelated':
                    return __('accounting-module.account-relation.discounts');
                    break;
                case '\App\AccountingModule\Models\AccountRelations\ActorsRelated':
                    return __('accounting-module.account-relation.'.$this->related_actor->actor_type.'s');
                    break;
            }
        } catch (Exception $e) {
            return '';
        }
    }

    function GetCustomAccountTypeAttribute() {
        try {
            switch ($this->related_model_name) {
                case '\App\AccountingModule\Models\AccountRelations\LockersBanksRelated':
                    return $this->related_locker_bank->related_as == 'locker' ? __('Locker') : __('Account');
                    break;
                case '\App\AccountingModule\Models\AccountRelations\StorePermissionRelated':
                    return $this->related_stores_permission->permission_nature == 'exchange' ?
                        __('accounting-module.exchange-permission') : __('accounting-module.receive-permission');
                    break;
                case '\App\AccountingModule\Models\AccountRelations\StoresRelated':
                    return $this->related_store->related_as == 'store' ?
                        __('accounting-module.related-to-store') : __('accounting-module.related-to-branch-stores');
                    break;
                case '\App\AccountingModule\Models\AccountRelations\MoneyPermissionsRelated':
                    return $this->related_money_permission->act_as == 'receive' ?
                        __('accounting-module.receive-permission')
                        :
                        __('accounting-module.exchange-permission');
                    break;
                case '\App\AccountingModule\Models\AccountRelations\ExpensesRevenuesRelated':
                    return $this->related_expense_revenue->related_as == 'credit' ?
                        $this->related_expense_revenue->revenue_type->type
                        :
                        $this->related_expense_revenue->expense_type->type;
                    return __('accounting-module.account-relation.types-items');
                    break;
                case '\App\AccountingModule\Models\AccountRelations\ActorsRelated':
                    return __('accounting-module.'.$this->related_actor->related_as);
                    break;
                case '\App\AccountingModule\Models\AccountRelations\TaxesRelated':
                    return __('accounting-module.account-relation.taxes');
                    break;
                case '\App\AccountingModule\Models\AccountRelations\DiscountRelated':
                    return __('accounting-module.discount-'.$this->related_discounts->discount_type);
                    break;
            }
        } catch (Exception $e) {
            return '';
        }
    }

    function GetCustomAccountItemAttribute() {
        try {
            switch ($this->related_model_name) {
                case '\App\AccountingModule\Models\AccountRelations\LockersBanksRelated':
                    return $this->related_locker_bank->related_as == 'locker' ?
                        $this->related_locker_bank->locker->name
                        :
                        $this->related_locker_bank->bank->name;
                    break;
                case '\App\AccountingModule\Models\AccountRelations\TaxesRelated':
                    return $this->related_taxes->tax->name;
                    break;
                case '\App\AccountingModule\Models\AccountRelations\MoneyPermissionsRelated':
                    return $this->related_money_permission->money_gateway == 'locker' ? __('Locker') : __('Account');
                    break;
                case '\App\AccountingModule\Models\AccountRelations\StoresRelated':
                    return $this->related_store->related_as == 'store' ?
                        $this->related_store->store->name : $this->related_store->stores_branch->name;
                    break;
                case '\App\AccountingModule\Models\AccountRelations\StorePermissionRelated':
                    return __('Stores');
                    break;
                case '\App\AccountingModule\Models\AccountRelations\DiscountRelated':
                    return __('accounting-module.discount-'.$this->related_discounts->discount_type);
                    break;
                case '\App\AccountingModule\Models\AccountRelations\ExpensesRevenuesRelated':
                    return $this->related_expense_revenue->related_as == 'credit' ?
                        $this->related_expense_revenue->revenue_item->item
                        :
                        $this->related_expense_revenue->expense_item->item;
                    break;
                case '\App\AccountingModule\Models\AccountRelations\ActorsRelated':
                    if ($this->related_actor->actor_type == 'customer') {
                        if ($this->related_actor->related_as == 'actor_group') {
                            return $this->related_actor->customer_group->name;
                        } elseif ($this->related_actor->related_as == 'actor_id') {
                            return $this->related_actor->customer->name;
                        }
                        return '';
                    } elseif ($this->related_actor->actor_type == 'supplier') {
                        if ($this->related_actor->related_as == 'actor_group') {
                            return $this->related_actor->supplier_group->name;
                        } elseif ($this->related_actor->related_as == 'actor_id') {
                            return $this->related_actor->supplier->name;
                        }
                        return '';
                    } else {
                        if ($this->related_actor->related_as == 'actor_group') {
                            return app()->getLocale() == 'ar' ?
                                $this->related_actor->employee_group->name_ar : $this->related_actor->employee_group->name_en;
                        } elseif ($this->related_actor->related_as == 'actor_id') {
                            return app()->getLocale() == 'ar' ?
                                $this->related_actor->employee->name_ar : $this->related_actor->employee->name_en;
                        }
                        return '';
                    }
                    break;
            }
        } catch (Exception $e) {
            return '';
        }
    }
}