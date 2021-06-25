<?php
namespace App\AccountingModule\Controllers;

use Exception;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\AccountingModule\Models\FiscalYear;
use App\AccountingModule\Models\DailyRestriction;

use App\AccountingModule\Controllers\AccountsTree;

use App\AccountingModule\Models\AdverseRestrictionLog;
use App\AccountingModule\Models\DailyRestrictionTable;
use App\AccountingModule\CoreLogic\AccountRelationLogic;
use App\Http\Requests\AccountingModule\AdverseRestrictionStoreRequest;
use App\Http\Requests\AccountingModule\AdverseRestrictionReq;
use App\AccountingModule\Models\AccountsTree as AccountsTreeModel;

class AdverseRestriction extends Controller {
    protected $operation_number ,$restriction_number;

    function __construct() {
        // $this->middleware('permission:view_adverse-restrictions' ,['except' => ['build_tree_ids_array' ,'build_tree_options' ,'build_tree' ,'run_build_tree']]);
        // $this->middleware(
        //     'permission:create_adverse-restrictions',
        //     [
        //         'only' => [
        //             'save','store','getExpense_N_RevenueAcc','accounts_data_collection','check_period_availability','get_last_numbers'
        //         ]
        //     ]
        // );

        $this->rows = 1;
        $this->debit_total = 0;
        $this->credit_total = 0;
    }

    private function load_account_tree_by_root_level($code = 2) {
        $code = !in_array($code ,[1, 2, 3, 4, 5]) ? 2 : $code;
        $options = '<option value="">'.__('accounting-module.select-one').'</option>';
        $root_acc = AccountsTreeModel::where('code' ,$code)->where('branch_id' ,auth()->user()->branch_id)->first();
        if ($root_acc) {
            AccountsTree::build_tree_options($root_acc ,$options);
        }
        return $options;
    }

    function form() {
        if (!auth()->user()->can('view_adverse-restrictions')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        $lang = app()->getLocale();
        $years = FiscalYear::select(($lang == 'ar' ? 'name_ar' : 'name_en').' as name' ,'id' ,'start_date' ,'end_date')->orderBy('id' ,'desc');

        return view('accounting-module.adverse-restriction' ,[
            'root_options' => '',
            'tree_options' => $this->load_account_tree_by_root_level(),
            'years' => $years
        ]);
    }

    function save(AdverseRestrictionReq $request) {
        if (!auth()->user()->can('create_adverse-restrictions')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        if($this->check_period_availability($request->acc_tree_id ,$request->date_from ,$request->date_to)) {
            return redirect()->back()->withInput()->with(['message' => __('accounting-module.adverse-restriction-exists') ,'alert-type' => 'warning']);
        }
        $this->get_last_numbers();
        $operation_number = $this->operation_number;
        $restriction_number = $this->restriction_number;
        $fiscal_year = $request->fiscal_year;
        $operation_date = $request->date;
        $account = AccountsTreeModel::find($request->acc_tree_id);
        $collection = $this->accounts_data_collection(
            AccountRelationLogic::get_expenses_revenues_accounts() ,
            $request->date_from ,
            $request->date_to ,
            $fiscal_year
        );
        if ($this->rows <= 1) return $this->return_fail();
        $balance = $this->debit_total - $this->credit_total;
        
        return view('accounting-module.daily-restrictions.adverse-restriction-create' ,[
            'view_path' => 'accounting-module.daily-restrictions',
            'collection' => $collection,
            'operation_number' => $operation_number,
            'restriction_number' => $restriction_number,
            'operation_date' => $operation_date,
            'account' => $account,
            'rows' => $this->rows,
            'debit_total' => $this->debit_total,
            'credit_total' => $this->credit_total,
            'balance' => $balance,
            'fiscal_year' => $fiscal_year,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to
        ]);
    }

    private function accounts_data_collection($tree_ids ,$date_from ,$date_to ,$fiscal_year) {
        $data = AccountsTreeModel
        ::leftJoin('daily_restriction_tables' ,'daily_restriction_tables.accounts_tree_id' ,'=' ,'accounts_trees.id')
        ->leftJoin('daily_restrictions' ,'daily_restrictions.id' ,'=' ,'daily_restriction_tables.daily_restriction_id')
        ->where('accounts_trees.accounts_tree_id' ,'!=' ,0)
        ->whereNull('daily_restriction_tables.deleted_at')
        ->whereNull('daily_restrictions.deleted_at')
        ->where('daily_restrictions.is_adverse' ,0)
        ->whereIn('accounts_trees.id' ,$tree_ids)
        ->where('daily_restrictions.fiscal_year_id' ,$fiscal_year)
        ->where('daily_restrictions.operation_date' ,'>=' ,$date_from)
        ->where('daily_restrictions.operation_date' ,'<=' ,$date_to);
        
        $temp = clone $data;
        $temp2 = clone $data;
        $this->rows = $temp->count() + 1;
        $this->credit_total = $temp2->sum('daily_restriction_tables.debit_amount');
        $this->debit_total = $temp2->sum('daily_restriction_tables.credit_amount');
        $lang = app()->getLocale();
        return $data->select(
            'accounts_trees.id as account_id',
            ($lang == 'ar' ? 'accounts_trees.name_ar' : 'accounts_trees.name_en').' as account_name',
            'accounts_trees.code as account_code',
            DB::raw('SUM(daily_restriction_tables.debit_amount) as debit_balance'),
            DB::raw('SUM(daily_restriction_tables.credit_amount) as credit_balance')
        )
        ->groupBy('account_id' ,'account_name' ,'account_code')
        ->orderBy('account_id' ,'asc');
    }

    private function check_period_availability($acc_id ,$start ,$end) {
        $start_exists = AdverseRestrictionLog::where('accounts_tree_id' ,$acc_id)
            ->where('date_from' ,'<=' ,$start)->where('date_to' ,'>=' ,$start)->first();
        $end_exists = AdverseRestrictionLog::where('accounts_tree_id' ,$acc_id)
            ->where('date_from' ,'<=' ,$end)->where('date_to' ,'>=' ,$end)->first();
        return $start_exists || $end_exists;
    }

    private function get_last_numbers() {
        $restriction_number = date('y') . '0000';
        $operation_number = 1;
        $last_restriction = DailyRestriction::withTrashed()->orderBy('id' ,'desc')->first();
        $last_operation_number = DailyRestriction::withTrashed()->orderBy('operation_number' ,'desc')->first();
        if ($last_restriction) {
            $restriction_number .= ($last_restriction->id + 1);
            try {
                $operation_number = $last_operation_number->operation_number + 1;
            } catch (Exception $e) {}
        } else $restriction_number .= '1';
        $this->operation_number = $operation_number;
        $this->restriction_number = $restriction_number;
    }

    function store(AdverseRestrictionStoreRequest $req) {
        if (!auth()->user()->can('create_adverse-restrictions')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        $data = $req->all();
        $table_data = $data['table_data'];
        unset($data['table_data']);
        $data['is_adverse'] = 1;
        $data['branch_id'] = auth()->user()->branch_id;
        $data['auto_generated'] = 1;
        $restriction = DailyRestriction::create($data);

        foreach($table_data as $row) {
            DailyRestrictionTable::create([
                'daily_restriction_id' => $restriction->id,
                'accounts_tree_id' => $row['accounts_tree_id'],
                'debit_amount' => $row['debit_amount'],
                'credit_amount' => $row['credit_amount'],
                'account_tree_code' => $row['account_tree_code']
            ]);
        }
        AdverseRestrictionLog::create([
            'date_from' => $req->date_from,
            'date_to' => $req->date_to,
            'fiscal_year' => $req->fiscal_year_id,
            'for_account_id' => $req->for_account_id,
            'date' => $req->operation_date,
            'branch_id' => auth()->user()->branch_id
        ]);

        return $this->return_success($req->date_from ,$req->date_to);
    }

    private function return_success($date_from ,$date_to) {
        return redirect(route('daily-restrictions.index'))
        ->with([
            'message' => 
            __('accounting-module.adverse-restriction-created').' '.
            __('accounting-module.from').' '.$date_from.' '.
            __('accounting-module.to').' '.$date_to
            , 'alert-type' => 'success'
        ]);
    }

    private function return_fail() {
        return redirect(route('adverse-restrictions'))
        ->with([
            'message' => __('accounting-module.no-adverse-restriction-for') ,
            'alert-type' => 'warning'
        ]);
    }
}