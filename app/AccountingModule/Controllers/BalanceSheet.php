<?php
namespace App\AccountingModule\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\AccountingModule\ExportCore\ExportFactory;
use App\AccountingModule\Models\AccountsTree as AccountsTreeModel;
use App\Scopes\BranchScope;

class BalanceSheet extends Controller {
    const view_path = "accounting-module.balance-sheet";
    const sort_by_columns = [
        'account_code', 'account_name', 'debit_balance', 'credit_balance'
    ];

    protected $accounts_tree_root_options;
    protected $filters;
    protected $totals;

    function __construct() {
        // $this->middleware('permission:view_balance-sheet-index' ,['except' => ['collection_query_builder' ,'load_accounts_tree' ,'prepare_filters']]);
    }

    function index() {
        if (!auth()->user()->can('view_balance-sheet-index')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        $this->prepare_filters();
        extract($this->filters);
        $this->load_accounts_tree($acc_root_id ,$branch_id);

        $tree_ids = [];
        if ($acc_root_id) {
            $acc = AccountsTreeModel::findOrFail($acc_root_id);
            AccountsTree::build_tree_ids_array($acc ,$tree_ids ,$branch_id);
        } else {
            AccountsTreeModel::whereIn('code' ,[1,2,3])->where('accounts_tree_id' ,0)->orderBy('id' ,'asc')
            ->when($branch_id ,function ($q) use ($branch_id) {
                $q->where('branch_id' ,$branch_id);
            })
            ->chunk(1 ,function ($accounts) use (&$tree_ids ,$branch_id) {
                foreach($accounts as $acc) {
                    AccountsTree::build_tree_ids_array($acc ,$tree_ids ,$branch_id);
                }
            });
        }

        $collection = $this->collection_query_builder($tree_ids ,$branch_id);
        
        if (isset($sort_by)) {
            $collection = $collection->orderBy($sort_by ,$sort_method);
        } else {
            $collection = $collection->orderBy('account_id' ,'desc');
        }

        if ($invoker && $invoker != 'search') {
            if ($invoker == 'print' && !auth()->user()->can('print_balance-sheet-index')) return redirect()->back();
            if ($invoker == 'excel' && !auth()->user()->can('export_balance-sheet-index')) return redirect()->back();
            $columns = self::sort_by_columns;
            $hidden_columns = isset($visible_columns) ? $visible_columns : [];
            $exportable = ExportFactory::build_exportable('BS-'.$invoker ,$collection ,$columns ,$hidden_columns);
            if ($invoker == 'print') $exportable->set_totals($this->totals);
            return $exportable->invoke_export();
        }

        $collection = $collection->paginate($rows)->appends(request()->query());

        return view(self::view_path.'.index' ,[
            'accounts_root_tree' => $this->accounts_tree_root_options,
            'view_path' => self::view_path,
            'collection' => $collection,
            'totals' => $this->totals,
            'sort_by_columns' => self::sort_by_columns
        ]);
    }

    private function prepare_filters() {
        $sort_method = isset($_GET['sort_method']) && $_GET['sort_method'] !=  '' ? $_GET['sort_method'] : 'asc';
        $sort_by = isset($_GET['sort_by']) && $_GET['sort_by'] !=  '' ? $_GET['sort_by'] : NULL;
        if ($sort_method && !in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'asc';
        if ($sort_by && !in_array($sort_by ,self::sort_by_columns)) $sort_by = self::sort_by_columns[0];

        if (authIsSuperAdmin()) {
            $branch_id = isset($_GET['branch_id']) && $_GET['branch_id'] !=  '' && $_GET['branch_id'] !=  -1 ? $_GET['branch_id'] : NULL;
        } else {
            $branch_id = auth()->user()->branch_id;
        }
        
        $this->filters = [
            'date_to' => isset($_GET['date']) && $_GET['date'] !=  '' ? $_GET['date'] : date('Y-m-d'),
            'acc_root_id' => isset($_GET['acc_root_id']) && $_GET['acc_root_id'] !=  '' ? $_GET['acc_root_id'] : NULL,
            'key' => isset($_GET['key']) && $_GET['key'] !=  '' ? $_GET['key'] : NULL,
            'sort_method' => $sort_method ? $sort_method : NULL,
            'sort_by' => $sort_by ? $sort_by : NULL,
            'invoker' => isset($_GET['invoker']) && $_GET['invoker'] != '' ? $_GET['invoker'] : NULL,
            'visible_columns' => isset($_GET['visible_columns']) && $_GET['visible_columns'] != '' ?
                $_GET['visible_columns'] : NULL,
            'rows' => isset($_GET['rows']) && $_GET['rows'] != '' ? $_GET['rows'] : 10,
            'with_adverse' => isset($_GET['with_adverse']) && $_GET['with_adverse'] == 'yes' ? true : false,
            'branch_id' => $branch_id
        ];
    }

    private function load_accounts_tree($acc_root_id ,$branch_id = NULL) {
        $root_tree_options = $tree_options = '<option value="">'.__('accounting-module.select-one').'</option>';
        $lang = app()->getLocale();
        AccountsTreeModel::where('accounts_tree_id' ,0)->whereIn('code' ,[1,2,3])
        ->where('tree_level' ,0)->orderBy('id' ,'asc')
        ->when($branch_id ,function ($q) use ($branch_id) {
            $q->where('branch_id' ,$branch_id);
        })
        ->chunk(1 ,function ($accounts) use (&$tree_options ,&$root_tree_options ,$acc_root_id ,$lang) {
            foreach($accounts as $acc) {
                $selected = $acc_root_id == $acc->id ? 'selected' : '';
                $root_tree_options .= '<option '.$selected.' value="'.$acc->id.'">'.($lang == 'ar' ? $acc->name_ar : $acc->name_en).'</option>';
            }
        });
        $this->accounts_tree_root_options = $root_tree_options;
    }

    private function collection_query_builder($tree_ids ,$branch_id = NULL) {
        extract($this->filters);
        
        $date = new \Carbon\Carbon($date_to);
        $date = $date->startOfYear()->toDateString();
        
        $data = AccountsTreeModel
        ::withoutGlobalScope(BranchScope::class)
        ->leftJoin('daily_restriction_tables' ,'daily_restriction_tables.accounts_tree_id' ,'=' ,'accounts_trees.id')
        ->leftJoin('daily_restrictions' ,'daily_restrictions.id' ,'=' ,'daily_restriction_tables.daily_restriction_id')
        ->where('accounts_trees.accounts_tree_id' ,'!=' ,0)
        ->whereNull('daily_restriction_tables.deleted_at')
        ->whereNull('daily_restrictions.deleted_at')
        ->when($branch_id ,function ($q) use ($branch_id) {
            $q->where('accounts_trees.branch_id' ,$branch_id)->where('daily_restrictions.branch_id' ,$branch_id);
        })
        ->when($date_to ,function($subQ) use ($date ,$date_to) {
            $subQ->where('daily_restrictions.operation_date' ,'>=' ,$date)
            ->where('daily_restrictions.operation_date' ,'<=' ,$date_to);
        })
        ->when(!$with_adverse ,function ($q) {
            $q->where('daily_restrictions.is_adverse' ,0);
        })
        ->where(function ($q) use (&$tree_ids) {
            $q->whereIn('accounts_trees.id' ,$tree_ids);
        })
        ->when($key ,function ($q) use ($key) {
            $q->where(function($subQ) use ($key) {
                $subQ->where('accounts_trees.code' ,'like' ,"%$key%")
                ->orWhere('daily_restrictions.operation_date' ,'like' ,"%$key%")
                ->orWhere('accounts_trees.name_ar' ,'like' ,"%$key%")
                ->orWhere('accounts_trees.name_en' ,'like' ,"%$key%");
            });
        });
        $temp = clone $data;
        $this->totals = new \stdClass;
        $this->totals->total_debit = $temp->sum('daily_restriction_tables.debit_amount');
        $this->totals->total_credit = $temp->sum('daily_restriction_tables.credit_amount');
        $this->totals->balance = $this->totals->total_debit - $this->totals->total_credit;
        $lang = app()->getLocale();
        $acc_name_key = 'accounts_trees.'.($lang == 'ar' ? 'name_ar' : 'name_en');
        return $data->select(
            'accounts_trees.id as account_id',
            'accounts_trees.account_nature as account_nature',
            'accounts_trees.'.($lang == 'ar' ? 'name_ar' : 'name_en').' as account_name',
            'accounts_trees.code as account_code',
            DB::raw('SUM(daily_restriction_tables.debit_amount) as debit_balance'),
            DB::raw('SUM(daily_restriction_tables.credit_amount) as credit_balance')
        )
        ->groupBy('accounts_trees.id' ,'account_nature' ,$acc_name_key ,'accounts_trees.code');
    }
}