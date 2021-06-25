<?php
namespace App\AccountingModule\Controllers;

use App\AccountingModule\CoreLogic\AccountRelationLogic;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\AccountingModule\Models\FiscalYear;
use App\AccountingModule\Models\AccountRelation;
use App\AccountingModule\ExportCore\IncomeList\Excel;
use App\AccountingModule\ExportCore\IncomeList\Printer;
use App\AccountingModule\Models\AccountsTree as AccountsTreeModel;

class IncomeList extends Controller {
    const view_path = "accounting-module.income-list";
    const sort_by_columns = [
        'account_code', 'account_name', 'debit_balance', 'credit_balance'
    ];

    protected $filters;
    protected $revenue_totals;
    protected $expense_totals;

    function __construct() {
        // $this->middleware('permission:view_income-list-index' ,['except' => ['prepare_filters','collection_query_builder']]);

        $this->revenue_totals = new \stdClass;
        $this->revenue_totals->total_debit = 0;
        $this->revenue_totals->total_credit = 0;
        $this->revenue_totals->balance = 0;

        $this->expense_totals = new \stdClass;
        $this->expense_totals->total_debit = 0;
        $this->expense_totals->total_credit = 0;
        $this->expense_totals->balance = 0;
    }

    function index() {
        if (!auth()->user()->can('view_income-list-index')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        try {
            $this->prepare_filters();
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect(route('accounts-tree-index'))->with('warning' ,__('accounting-module.fiscal-years-is-not-set'));
        }
        extract($this->filters);

        $revenue_accounts = AccountRelationLogic::get_revenues_accounts($branch_id);
        $expense_accounts = AccountRelationLogic::get_expenses_accounts($branch_id);

        if (!empty($revenue_accounts)) {
            $revenue_collection = $this->collection_query_builder($revenue_accounts ,true ,$branch_id);
            $revenue_collection = $revenue_collection->orderBy('account_id' ,'desc');
        } else {
            $revenue_collection = NULL;
        }

        if (!empty($expense_accounts)) {
            $expense_collection = $this->collection_query_builder($expense_accounts ,false ,$branch_id);
            $expense_collection = $expense_collection->orderBy('account_id' ,'desc');
        } else {
            $expense_collection = NULL;
        }

        if ($invoker && $invoker != 'search') {
            if ($invoker == 'print' && !auth()->user()->can('print_income-list-index')) return redirect()->back();
            if ($invoker == 'excel' && !auth()->user()->can('export_income-list-index')) return redirect()->back();
            switch($invoker) {
                case "print":
                    $printer = new Printer(
                        $expense_collection ,$revenue_collection ,
                        $this->expense_totals ,$this->revenue_totals
                    );
                    return $printer->invoke_export();
                break;
                case "excel":
                    $excel = new Excel($expense_collection ,$revenue_collection);
                    return $excel->invoke_export();
                break;
            }
        }

        return view(self::view_path.'.index' ,[
            'view_path' => self::view_path,
            'revenue_collection' => $revenue_collection,
            'expense_collection' => $expense_collection,
            'revenue_totals' => $this->revenue_totals,
            'expense_totals' => $this->expense_totals,
            'sort_by_columns' => self::sort_by_columns
        ]);
    }

    private function prepare_filters() {
        $current_fiscal_year = FiscalYear::where('status', 1)->first();
        if (!$current_fiscal_year) {
            $current_fiscal_year = new \stdClass;
            $current_fiscal_year->start_date = date('Y-m-d');
            $current_fiscal_year->end_date = date('Y-m-d');
        }
        
        if (authIsSuperAdmin()) {
            $branch_id = isset($_GET['branch_id']) && $_GET['branch_id'] !=  '' && $_GET['branch_id'] !=  -1 ? $_GET['branch_id'] : NULL;
        } else {
            $branch_id = auth()->user()->branch_id;
        }

        $this->filters = [
            'date_from' => isset($_GET['date_from']) && $_GET['date_from'] !=  '' ?
                $_GET['date_from'] : $current_fiscal_year->start_date,
            'date_to' => isset($_GET['date_to']) && $_GET['date_to'] !=  '' ?
                $_GET['date_to'] : $current_fiscal_year->end_date,
            'invoker' => isset($_GET['invoker']) && $_GET['invoker'] != '' ? $_GET['invoker'] : NULL,
            'visible_columns' => isset($_GET['visible_columns']) && $_GET['visible_columns'] != '' ?
                $_GET['visible_columns'] : NULL,
            'with_adverse' => isset($_GET['with_adverse']) && $_GET['with_adverse'] == 'yes' ? true : false,
            'branch_id' => $branch_id
        ];
    }

    private function collection_query_builder($tree_ids ,$for_expense = false ,$branch_id) {
        extract($this->filters);
        
        $data = AccountsTreeModel
        ::leftJoin('daily_restriction_tables' ,'daily_restriction_tables.accounts_tree_id' ,'=' ,'accounts_trees.id')
        ->leftJoin('daily_restrictions' ,'daily_restrictions.id' ,'=' ,'daily_restriction_tables.daily_restriction_id')
        ->where('accounts_trees.accounts_tree_id' ,'!=' ,0)
        ->whereNull('daily_restriction_tables.deleted_at')
        ->whereNull('daily_restrictions.deleted_at')
        ->when($branch_id ,function ($q) use ($branch_id) {
            $q->where('accounts_trees.branch_id' ,$branch_id)->where('daily_restrictions.branch_id' ,$branch_id);
        })
        ->when(!$with_adverse ,function ($q) {
            $q->where('daily_restrictions.is_adverse' ,0);
        })
        ->when($date_from ,function($subQ) use ($date_from ,$date_to) {
            $subQ->where('daily_restrictions.operation_date' ,'>=' ,$date_from)
            ->where('daily_restrictions.operation_date' ,'<=' ,$date_to);
        })
        ->when(!empty($tree_ids) ,function ($q) use ($tree_ids) {
            $q->whereIn('accounts_trees.id' ,$tree_ids);
        });
        $temp = clone $data;
        $custom_total_key = $for_expense ? 'revenue_totals' : 'expense_totals';
        $this->$custom_total_key = new \stdClass;
        $this->$custom_total_key->total_debit = $temp->sum('daily_restriction_tables.debit_amount');
        $this->$custom_total_key->total_credit = $temp->sum('daily_restriction_tables.credit_amount');
        $this->$custom_total_key->balance =
            $this->$custom_total_key->total_debit - $this->$custom_total_key->total_credit;
        $lang = app()->getLocale();
        return $data->select(
            'accounts_trees.id as account_id',
            'accounts_trees.'.($lang == 'ar' ? 'name_ar' : 'name_en').' as account_name',
            'accounts_trees.code as account_code',
            DB::raw('SUM(daily_restriction_tables.debit_amount) as debit_balance'),
            DB::raw('SUM(daily_restriction_tables.credit_amount) as credit_balance')
        )
        ->groupBy('account_id' ,'account_name' ,'account_code');
    }
}