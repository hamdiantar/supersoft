<?php
namespace App\AccountingModule\Controllers;

use App\Http\Controllers\Controller;
use App\AccountingModule\Models\AccountsTree;
use App\AccountingModule\Traits\AccountRelationTaxTrait;

use App\AccountingModule\Models\AccountRelation as Model;

use App\AccountingModule\Traits\AccountRelationCustomers;
use App\AccountingModule\Traits\AccountRelationEmployees;
use App\AccountingModule\Traits\AccountRelationSuppliers;
use App\AccountingModule\Traits\AccountRelationTypesItems;
use App\AccountingModule\Traits\AccountRelationGlobalTrait;
use App\AccountingModule\Traits\AccountRelationStoresTrait;
use App\AccountingModule\Traits\AccountRelationLockersBanks;
use App\AccountingModule\Traits\AccountRelationMoneyPermissions;
use App\AccountingModule\Traits\AccountRelationStorePermissions;
use App\AccountingModule\Controllers\AccountsTree as AccountsTreeCont;
use App\AccountingModule\Traits\AccountRelationDiscountTrait;

class AccountRelations extends Controller {
    use AccountRelationMoneyPermissions, AccountRelationTypesItems, AccountRelationLockersBanks,
        AccountRelationCustomers, AccountRelationSuppliers, AccountRelationEmployees,
        AccountRelationGlobalTrait, AccountRelationStorePermissions, AccountRelationStoresTrait,
        AccountRelationTaxTrait ,AccountRelationDiscountTrait;
        
    const view_path = "accounting-module.account-relations";
    const module_index = "account-relations.index";

    function __construct() {
        $this->main_view_path = self::view_path;
    }

    function index() {
        if (!auth()->user()->can('view_account-relations')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $rows = 10;
        $lang = app()->getLocale() == 'ar' ? 'ar' : 'en';
        $collection = Model::orderBy('id' ,'desc')
        ->with([
            'related_money_permission', 'related_discounts',
            'related_stores_permission',
            'related_locker_bank' => function ($q) {
                $q->with([
                    'locker' => function ($subQ) {
                        $subQ->select('id' ,'name_en' ,'name_ar');
                    },
                    'bank' => function ($subQ) {
                        $subQ->select('id' ,'name_en' ,'name_ar');
                    }
                ]);
            },
            'related_store' => function ($q) {
                $q->with([
                    'store' => function ($subQ) {
                        $subQ->select('id' ,'name_en' ,'name_ar');
                    },
                    'stores_branch' => function ($subQ) {
                        $subQ->select('id' ,'name_en' ,'name_ar');
                    }
                ]);
            },
            'related_taxes' => function ($q) {
                $q->with([
                    'tax' => function ($subQ) {
                        $subQ->select('id' ,'name_en' ,'name_ar');
                    }
                ]);
            },
            'related_expense_revenue' => function ($q) {
                $q->with([
                    'expense_type' => function ($subQ) {
                        $subQ->select('id' ,'type_ar' ,'type_en');
                    },
                    'expense_item' => function ($subQ) {
                        $subQ->select('id' ,'item_ar' ,'item_en');
                    },
                    'revenue_type' => function ($subQ) {
                        $subQ->select('id' ,'type_ar' ,'type_en');
                    },
                    'revenue_item' => function ($subQ) {
                        $subQ->select('id' ,'item_ar' ,'item_en');
                    }
                ]);
            },
            'related_actor' => function ($q) {
                $q->with([
                    'customer' => function ($subQ) {
                        $subQ->select('id' ,'name_ar' ,'name_en');
                    },
                    'customer_group' => function ($subQ) {
                        $subQ->select('id' ,'name_ar' ,'name_en');
                    },
                    'supplier' => function ($subQ) {
                        $subQ->select('id' ,'name_ar' ,'name_en');
                    },
                    'supplier_group' => function ($subQ) {
                        $subQ->select('id' ,'name_ar' ,'name_en');
                    },
                    'employee' => function ($subQ) {
                        $subQ->select('id' ,'name_ar' ,'name_en');
                    },
                    'employee_group' => function ($subQ) {
                        $subQ->select('id' ,'name_ar' ,'name_en');
                    }
                ]);
            }
        ])
        ->paginate($rows)->appends(request()->query());
        return view(self::view_path.'.index' ,compact('collection'));
    }

    function create() {
        if (!auth()->user()->can('create_account-relations')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        $load_form = isset($_GET['action-for']) && $_GET['action-for'] != '' ? $_GET['action-for'] : 'types-items';
        switch($load_form) {
            case 'money-permissions':
                return $this->create_money_permission();
                break;
            case 'lockers-banks':
                return $this->create_lockers_banks();
                break;
            case 'customers':
                return $this->create_customers();
                break;
            case 'employees':
                return $this->create_employees();
                break;
            case 'suppliers':
                return $this->create_suppliers();
                break;
            case 'stores-permissions':
                return $this->create_stores_permission();
                break;
            case 'stores':
                return $this->create_stores_relation();
                break;
            case 'taxes':
                return $this->create_taxes_related();
                break;
            case 'discounts':
                return $this->create_discounts_related();
                break;
            default:
                return $this->create_types_items();
                break;
        }
    }

    function edit ($id) {
        if (!auth()->user()->can('edit_account-relations')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $model = Model::findOrFail($id);
        switch($model->related_model_name) {
            case '\App\AccountingModule\Models\AccountRelations\MoneyPermissionsRelated':
                return $this->edit_money_permission($model);
                break;
            case '\App\AccountingModule\Models\AccountRelations\StorePermissionRelated':
                return $this->edit_stores_permission($model);
                break;
            case '\App\AccountingModule\Models\AccountRelations\StoresRelated':
                return $this->edit_stores_relation($model);
                break;
            case '\App\AccountingModule\Models\AccountRelations\TaxesRelated':
                return $this->edit_taxes_related($model);
                break;
            case '\App\AccountingModule\Models\AccountRelations\LockersBanksRelated':
                return $this->edit_lockers_banks($model);
                break;
            case '\App\AccountingModule\Models\AccountRelations\DiscountRelated':
                return $this->edit_discounts_related($model);
                break;
            case '\App\AccountingModule\Models\AccountRelations\ActorsRelated':
                if ($model->related_actor->actor_type == 'customer')
                    return $this->edit_customers($model);
                else if ($model->related_actor->actor_type == 'employee')
                    return $this->edit_employees($model);
                else if ($model->related_actor->actor_type == 'supplier')
                    return $this->edit_suppliers($model);
                break;
            case '\App\AccountingModule\Models\AccountRelations\ExpensesRevenuesRelated':
                return $this->edit_types_items($model);
                break;
        }
        return redirect(route('admin:account-relations.index'));
    }

    function delete($id) {
        if (!auth()->user()->can('delete_account-relations')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $model = Model::findOrFail($id);
        switch($model->related_model_name) {
            case '\App\AccountingModule\Models\AccountRelations\LockersBanksRelated':
                $model->related_locker_bank->delete();
                break;
            case '\App\AccountingModule\Models\AccountRelations\MoneyPermissionsRelated':
                $model->related_money_permission->delete();
                break;
            case '\App\AccountingModule\Models\AccountRelations\StorePermissionRelated':
                $model->related_stores_permission->delete();
                break;
            case '\App\AccountingModule\Models\AccountRelations\StoresRelated':
                $model->related_store->delete();
                break;
            case '\App\AccountingModule\Models\AccountRelations\ExpensesRevenuesRelated':
                $model->related_expense_revenue->delete();
                break;
            case '\App\AccountingModule\Models\AccountRelations\ActorsRelated':
                $model->related_actor->delete();
                break;
            case '\App\AccountingModule\Models\AccountRelations\TaxesRelated':
                $model->related_taxes->delete();
                break;
            case '\App\AccountingModule\Models\AccountRelations\DiscountRelated':
                $model->related_discounts->delete();
                break;
            // most add others after finalize its implementation
        }
        $model->delete();
        return redirect(route(self::module_index))->with(['message' => __('accounting-module.account-relation-deleted') ,'alert-type' => 'success']);
    }

    function fetchRootBranches() {
        $root_id = isset($_GET['root_id']) && $_GET['root_id'] != '' ? $_GET['root_id'] : NULL;
        $account_id = isset($_GET['account_id']) && $_GET['account_id'] != '' ? $_GET['account_id'] : NULL;
        if ($root_id == NULL) return response(['message' => __('accounting-module.server-error')] ,400);
        $html_options = '<option value=""> '. __('accounting-module.select-one') .' </option>';
        $root_account = AccountsTree::findOrFail($root_id);
        AccountsTreeCont::build_tree_options($root_account ,$html_options ,$account_id);
        return response(['html_options' => $html_options]);
    }

    function checkRelUnique() {
        $load_form = request()->has('action_for') && request('action_for') != '' ? request('action_for') : 'types-items';
        switch($load_form) {
            case 'money-permissions':
                return $this->relation_money_permission_unique();
                break;
            case 'lockers-banks':
                return $this->relation_lockers_banks_unique();
                break;
            case 'customers':
                return $this->relation_customers_unique();
                break;
            case 'employees':
                return $this->relation_employees_unique();
                break;
            case 'suppliers':
                return $this->relation_suppliers_unique();
                break;
            case 'stores-permissions':
                return $this->relation_stores_permission_unique();
                break;
            case 'stores':
                return $this->relation_stores_relation_unique();
                break;
            case 'taxes':
                return $this->relation_taxes_related_unique();
                break;
            case 'discounts':
                return $this->relation_discounts_related_unique();
                break;
            default:
                return $this->relation_types_items_unique();
                break;
        }
    }
    
}

