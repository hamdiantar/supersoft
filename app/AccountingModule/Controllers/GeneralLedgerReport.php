<?php
namespace App\AccountingModule\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\AccountingModule\Models\CostCenter;
use App\AccountingModule\ExportCore\ExportFactory;
use App\AccountingModule\Models\DailyRestrictionTable;
use App\AccountingModule\Models\AccountsTree as AccountsTreeModel;

class GeneralLedgerReport extends Controller {
    const view_path = "accounting-module.general-ledger";
    const sort_by_columns = [
        'restriction_number', 'operation_date', 'debit_amount', 'credit_amount',
        'balance', 'account_name', 'account_code', 'cost_center'
    ];

    protected $cost_centers_root_options;
    protected $cost_centers_options;
    protected $accounts_tree_root_options;
    protected $accounts_tree_options;
    protected $filters;

    function __construct() {
        // $this->middleware('permission:view_accounting-general-ledger' ,[
        //     'except' => [
        //         'prepare_filters', 'load_cost_cetners', 'load_accounts_tree',
        //         'collection_query_builder', 'load_account_tree', 'load_center_tree', 'get_account_tree_options'
        //     ]
        // ]);
    }

    function index() {

        
        if (!auth()->user()->can('view_accounting-general-ledger')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $this->prepare_filters();
        extract($this->filters);
        $this->load_accounts_tree($acc_root_id ,$acc_tree_id ,$branch_id);
        $this->load_cost_cetners($cost_root_id ,$cost_id ,$branch_id);

        $show_cost_center = false;
        if ($cost_root_id || $cost_id) $show_cost_center = true;

        $tree_ids = [];
        if ($acc_root_id) {
            $acc = AccountsTreeModel::findOrFail($acc_root_id);
            AccountsTree::build_tree_ids_array($acc ,$tree_ids ,$branch_id);
        }
        if ($acc_tree_id) {   
            $tree_ids = [$acc_tree_id];
            // $acc = AccountsTreeModel::findOrFail($acc_tree_id);
            // AccountsTree::build_tree_ids_array($acc ,$tree_ids);
        }

        $centers_ids = [];
        if ($cost_root_id) {
            $cost = CostCenter::findOrFail($cost_root_id);
            $centers_ids = [$cost_root_id];
            CostCenterCont::build_centers_ids_array($cost ,$centers_ids,$branch_id);
        }
        if ($cost_id) {
            $centers_ids = [$cost_id];
            $cost = CostCenter::findOrFail($cost_id);
            CostCenterCont::build_centers_ids_array($cost ,$centers_ids,$branch_id);
        }

        $data = $this->collection_query_builder($centers_ids ,$tree_ids ,$branch_id);
        
        $temp = $data['temp'];
        $collection = $data['collection'];
        $totals = new \stdClass;
        $totals->total_debit = $temp->sum('daily_restriction_tables.debit_amount');
        $totals->total_credit = $temp->sum('daily_restriction_tables.credit_amount');
        $totals->balance = $totals->total_debit - $totals->total_credit;
        if (isset($sort_by)) {
            $collection = $collection->orderBy($sort_by ,$sort_method);
        } else {
            $collection = $collection->orderBy('row_id' ,'desc');
        }

        if ($invoker && $invoker != 'search') {
            if ($invoker == 'print' && !auth()->user()->can('print_accounting-general-ledger')) return redirect()->back();
            if ($invoker == 'excel' && !auth()->user()->can('export_accounting-general-ledger')) return redirect()->back();
            $columns = self::sort_by_columns;
            $hidden_columns = isset($visible_columns) ? $visible_columns : [];
            $exportable = ExportFactory::build_exportable('GL-'.$invoker ,$collection ,$columns ,$hidden_columns);
            if ($invoker == 'print') $exportable->set_totals($totals);
            $exportable->set_show_cost($show_cost_center);
            return $exportable->invoke_export();
        }

        $collection = $collection->paginate($rows)->appends(request()->query());

        return view(self::view_path.'.index' ,[
            'accounts_root_tree' => $this->accounts_tree_root_options,
            'accounts_tree' => $this->accounts_tree_options,
            'cost_centers_root' => $this->cost_centers_root_options,
            'cost_centers' => $this->cost_centers_options,
            'view_path' => self::view_path,
            'show_cost_center' => $show_cost_center,
            'collection' => $collection,
            'totals' => $totals
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
            'date_from' => isset($_GET['date_from']) && $_GET['date_from'] !=  '' ? $_GET['date_from'] : NULL,
            'date_to' => isset($_GET['date_to']) && $_GET['date_to'] !=  '' ? $_GET['date_to'] : date('m/d/Y'),
            'acc_root_id' => isset($_GET['acc_root_id']) && $_GET['acc_root_id'] !=  '' ? $_GET['acc_root_id'] : NULL,
            'acc_tree_id' => isset($_GET['acc_tree_id']) && $_GET['acc_tree_id'] !=  '' ? $_GET['acc_tree_id'] : NULL,
            'cost_root_id' => isset($_GET['cost_root_id']) && $_GET['cost_root_id'] !=  '' ? $_GET['cost_root_id'] : NULL,
            'cost_id' => isset($_GET['cost_id']) && $_GET['cost_id'] !=  '' ? $_GET['cost_id'] : NULL,
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

    private function load_cost_cetners($cost_root_id ,$cost_id ,$branch_id) {
        $this->cost_centers_root_options = CostCenterCont::build_root_centers_options($cost_root_id ,$branch_id);
        $this->cost_centers_options = CostCenterCont::build_centers_options($cost_id ,NULL ,1 ,false ,$branch_id);
    }

    private function load_accounts_tree($acc_root_id ,$acc_tree_id ,$branch_id) {
        $root_tree_options = $tree_options = '<option value="">'.__('accounting-module.select-one').'</option>';
        $lang = app()->getLocale();
        AccountsTreeModel::where('accounts_tree_id' ,0)
        ->when($branch_id ,function ($q) use ($branch_id) {
            $q->where('branch_id' ,$branch_id);
        })
        ->where('tree_level' ,0)->orderBy('id' ,'asc')
        ->chunk(1 ,function ($accounts) use (&$tree_options ,&$root_tree_options ,$acc_root_id ,$acc_tree_id ,$lang) {
            foreach($accounts as $acc) {
                $selected = $acc_root_id == $acc->id ? 'selected' : '';
                $root_tree_options .= '<option '.$selected.' value="'.$acc->id.'">'.($lang == 'ar' ? $acc->name_ar : $acc->name_en).'</option>';
                AccountsTree::build_tree_options($acc, $tree_options, $acc_tree_id);
            }
        });
        $this->accounts_tree_root_options = $root_tree_options;
        $this->accounts_tree_options = $tree_options;
    }

    private function collection_query_builder($centers_ids ,$tree_ids ,$branch_id = NULL) {
        extract($this->filters);
        
        $data = DailyRestrictionTable
        ::leftJoin('daily_restrictions' ,'daily_restrictions.id' ,'=' ,'daily_restriction_tables.daily_restriction_id')
        ->leftJoin('accounts_trees' ,'accounts_trees.id' ,'=' ,'daily_restriction_tables.accounts_tree_id')
        ->leftJoin('cost_centers' ,'cost_centers.id' ,'=' ,'daily_restriction_tables.cost_center_id')
        ->when(!$with_adverse ,function ($q) {
            $q->where('daily_restrictions.is_adverse' ,0);
        })
        ->when($branch_id ,function ($q) use ($branch_id) {
            $q->where('daily_restrictions.branch_id' ,$branch_id);
        })
        ->when($date_from ,function($subQ) use ($date_from ,$date_to) {
            $subQ->where('daily_restrictions.operation_date' ,'>=' ,$date_from)
            ->where('daily_restrictions.operation_date' ,'<=' ,$date_to);
        })
        ->when($acc_root_id ,function ($q) use ($tree_ids) {
            if (empty($tree_ids)) $tree_ids[] = -1;
            $q->whereIn('daily_restriction_tables.accounts_tree_id' ,$tree_ids);
        })
        ->when($acc_tree_id ,function ($q) use ($acc_tree_id) {
            $q->where('daily_restriction_tables.accounts_tree_id' ,$acc_tree_id);
        })
        ->when($cost_root_id ,function ($q) use ($centers_ids) {
            if (empty($centers_ids)) $centers_ids[] = -1;
            $q->whereIn('daily_restriction_tables.cost_center_id' ,$centers_ids);
        })
        ->when($cost_id ,function ($q) use ($centers_ids) {
            if (empty($centers_ids)) $centers_ids[] = -1;
            $q->whereIn('daily_restriction_tables.cost_center_id' ,$centers_ids);
        })
        ->when($key ,function ($q) use ($key) {
            $q->where(function($subQ) use ($key) {
                $subQ->where('daily_restrictions.restriction_number' ,'like' ,"%$key%")
                ->orWhere('daily_restrictions.operation_date' ,'like' ,"%$key%")
                ->orWhere('daily_restriction_tables.debit_amount' ,'like' ,"%$key%")
                ->orWhere('daily_restriction_tables.credit_amount' ,'like' ,"%$key%")
                ->orWhere('accounts_trees.name_ar' ,'like' ,"%$key%")
                ->orWhere('cost_centers.name_ar' ,'like' ,"%$key%")
                ->orWhere('accounts_trees.name_en' ,'like' ,"%$key%")
                ->orWhere('cost_centers.name_en' ,'like' ,"%$key%");
            });
        });
        $temp = clone $data;
        $lang = app()->getLocale();
        $data = $data
        ->select(
            'daily_restrictions.restriction_number as restriction_number',
            'daily_restrictions.operation_date as operation_date',
            'daily_restriction_tables.debit_amount as debit_amount',
            'daily_restriction_tables.credit_amount as credit_amount',
            DB::raw('daily_restriction_tables.debit_amount - daily_restriction_tables.credit_amount as balance'),
            'accounts_trees.'.($lang == 'ar' ? 'name_ar' : 'name_en').' as account_name',
            'daily_restriction_tables.account_tree_code as account_code',
            'cost_centers.'.($lang == 'ar' ? 'name_ar' : 'name_en').' as cost_center',
            'daily_restriction_tables.cost_center_code as cost_center_code',
            'daily_restriction_tables.id as row_id',
            'daily_restriction_tables.cost_center_id as cost_center_id'
        );
        return ['collection' => $data ,'temp' => $temp];
    }

    function load_account_tree() {
        $account_id = isset($_GET['tree_id']) && $_GET['tree_id'] != '' ? $_GET['tree_id'] : NULL;
        $selected = isset($_GET['selected']) && $_GET['selected'] != '' && $_GET['selected'] != 'undefined'
            ? $_GET['selected'] : NULL;
        $tree_options = '<option value="">'.__('accounting-module.select-one').'</option>';
        if ($account_id == NULL) {
            AccountsTreeModel::where('accounts_tree_id' ,0)
            ->where('tree_level' ,0)->orderBy('id' ,'asc')
            ->chunk(1 ,function ($accounts) use (&$tree_options ,$selected) {
                foreach($accounts as $acc) {
                    AccountsTree::build_tree_options($acc, $tree_options, $selected);
                }
            });
        } else {
            $acc = AccountsTreeModel::findOrFail($account_id);
            AccountsTree::build_tree_options($acc, $tree_options, $selected);
        }
        return response(['tree_options' => $tree_options]);
    }

    function load_center_tree() {
        $root_cost_id = isset($_GET['root_id']) && $_GET['root_id'] != '' ? $_GET['root_id'] : NULL;
        $selected = isset($_GET['selected']) && $_GET['selected'] != '' && $_GET['selected'] != 'undefined'
            ? $_GET['selected'] : NULL;
        $count = 1;
        if ($root_cost_id) {
            CostCenter::orderBy('id' ,'asc')->chunk(50 ,function($centers) use (&$count ,$root_cost_id) {
                foreach($centers as $cost) {
                    if ($cost->id == $root_cost_id) return;
                    $count++;
                }
            });
        }
        $centers_options = CostCenterCont::build_centers_options($selected ,$root_cost_id ,$count);
        return response(['centers_options' => $centers_options]);
    }

    function get_account_tree_options($root_id ,$tree_id) {
        $branch_id = authIsSuperAdmin() ? NULL : auth()->user()->branch_id;
        $this->load_accounts_tree($root_id ,$tree_id ,$branch_id);
        return [
            'root_tree_options' => $this->accounts_tree_root_options,
            'tree_options' => $this->accounts_tree_options
        ];
    }
}