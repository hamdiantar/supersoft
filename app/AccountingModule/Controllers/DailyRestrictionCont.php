<?php
namespace App\AccountingModule\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\AccountingModule\Models\FiscalYear;
use App\AccountingModule\Models\AccountsTree;
use App\AccountingModule\ExportCore\ExportFactory;
use App\AccountingModule\Models\DailyRestrictionTable;
use App\AccountingModule\Models\DailyRestriction as Model;

use App\Http\Requests\AccountingModule\DailyRestrictionsReq;
use App\Http\Requests\AccountingModule\DailyRestrictionsForAccTree;

class DailyRestrictionCont extends Controller {

    const view_path = "accounting-module.daily-restrictions";

    function __construct() {
        // $this->middleware('permission:view_daily-restrictions' ,['except' => ['prepare_index_get_inputs', 'build_tree', 'get_tree_as_table']]);
        // $this->middleware('permission:create_daily-restrictions',['only'=>['create','store']]);
        // $this->middleware('permission:edit_daily-restrictions',['only'=>['edit','update' ,'update_for_account_tree']]);
        // $this->middleware('permission:delete_daily-restrictions',['only'=>['delete']]);
        // $this->middleware('permission:print_daily-restrictions',['only'=>['print']]);
    }

    private function prepare_index_get_inputs() {
        $return['date_from'] = isset($_GET['date_from']) && $_GET['date_from'] != '' ? $_GET['date_from'] : NULL;
        $return['date_to'] = isset($_GET['date_to']) && $_GET['date_to'] != '' ? $_GET['date_to'] : date('Y-m-d');
        $return['id'] = isset($_GET['id']) && $_GET['id'] != '' ? $_GET['id'] : NULL;
        $return['rows'] = isset($_GET['rows']) && $_GET['rows'] != '' ? $_GET['rows'] : 10;
        $return['key'] = isset($_GET['key']) && $_GET['key'] != '' ? $_GET['key'] : NULL;

        $return['sort_by'] = isset($_GET['sort_by']) && $_GET['sort_by'] != '' ? $_GET['sort_by'] : NULL;
        $return['sort_method'] = isset($_GET['sort_method']) && $_GET['sort_method'] != '' ? $_GET['sort_method'] : 'asc';
        if (!in_array($return['sort_method'] ,['asc' ,'desc'])) $return['sort_method'] = 'asc';
        if ( !in_array($return['sort_by'] ,
        [
            'restriction_number' ,'operation_number' ,'operation_date' ,'debit_amount' ,'credit_amount' ,'records_number'
        ]) ) $return['sort_by'] = NULL;

        $return['invoker'] = isset($_GET['invoker']) && $_GET['invoker'] != '' ? $_GET['invoker'] : NULL;
        $return['visible_columns'] = isset($_GET['visible_columns']) && $_GET['visible_columns'] != '' ?
            $_GET['visible_columns'] : NULL;
        $return['with_adverse'] = isset($_GET['with_adverse']) && $_GET['with_adverse'] == 'yes' ? true : false;
        return $return;
    }
    
    function index() {

        if (!auth()->user()->can('view_daily-restrictions')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        
        extract($this->prepare_index_get_inputs());

        $search_collection = Model::select('restriction_number' ,'id')->get();
        $collection = Model::when($id ,function ($q) use ($id) {
            $q->where('id' ,$id);
        })
        ->when($date_from ,function ($q) use ($date_from ,$date_to) {
            $q->where('operation_date' ,'>=' ,$date_from);
            $q->where('operation_date' ,'<=' ,$date_to);
        })
        ->when($key ,function ($q) use ($key) {
            $q->where(function ($s_q) use ($key) {
                $s_q->where('restriction_number' ,'like' ,"%$key%")
                ->orWhere('operation_number' ,'like' ,"%$key%")
                ->orWhere('operation_date' ,'like' ,"%$key%")
                ->orWhere('debit_amount' ,'like' ,"%$key%")
                ->orWhere('credit_amount' ,'like' ,"%$key%")
                ->orWhere('records_number' ,'like' ,"%$key%");
            });
        })
        ->when(!$with_adverse ,function ($q) {
            $q->where('is_adverse' ,0);
        })
        ->select(
            'restriction_number', 'operation_number', 'operation_date', 'debit_amount',
            'credit_amount', 'records_number', 'id', 'auto_generated'
        );
        $temp = clone $collection;
        $total = $temp->select(
            DB::raw('SUM(credit_amount) AS total_credit') ,
            DB::raw('SUM(debit_amount) AS total_debit')
        )->first();
        if ($sort_by) {
            $collection = $collection->orderBy($sort_by ,$sort_method);
        } else {
            $collection = $collection->orderBy('id' ,'desc');
        }
        if (isset($invoker) && $invoker != 'search') {
            if ($invoker == 'print' && !auth()->user()->can('print_daily-restrictions')) return redirect()->back();
            if ($invoker == 'excel' && !auth()->user()->can('export_daily-restrictions')) return redirect()->back();
            $columns = [
                'restriction_number', 'operation_number', 'operation_date', 'debit_amount',
                'credit_amount', 'records_number'
            ];
            $hidden_columns = isset($visible_columns) ? $visible_columns : [];
            $exportable = ExportFactory::build_exportable($invoker ,$collection ,$columns ,$hidden_columns);
            if ($invoker == 'print') $exportable->set_totals($total);
            return $exportable->invoke_export();
        }
        $collection = $collection->paginate($rows)->appends(request()->query());
        $view_path = self::view_path;
        return view(self::view_path.'.index' ,compact('search_collection' ,'collection' ,'total' ,'view_path'));
    }
    
    private static function get_tree_as_table() {
        $accounts_tree = AccountsTree::where('accounts_tree_id' ,0)->where('tree_level' ,0)->get();
        $accounts_tree_array = [];
        $count = 1;
        foreach($accounts_tree as $acc) {
            self::build_tree($acc ,$accounts_tree_array ,$count);
            $count++;
        }
        return $accounts_tree_array;
    }

    private static function build_tree($account, &$accounts_tree_array, $depth) {
        $count = 1;
        $name_key = app()->getLocale() == 'ar' ? 'name_ar' : 'name_en';
		foreach ($account->account_tree_branches as $child) {
            $obj = new \stdClass;
            $obj->id = $child->id;
            $obj->name = $child->$name_key;
            $obj->code = $depth.'.'.$count;
            array_push($accounts_tree_array ,$obj);
			if ($child->account_tree_branches) {
				self::build_tree($child, $accounts_tree_array, $depth.'.'.$count);
			}
            $count++;
		}
    }

    function create() {

        if (!auth()->user()->can('create_daily-restrictions')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $accounts_tree = self::get_tree_as_table();
        $restriction_number = date('y') . '0000';
        $last_restriction = Model::withTrashed()->orderBy('id' ,'desc')->first();
        if ($last_restriction) $restriction_number .= ($last_restriction->id + 1);
        else $restriction_number .= '1';
        $view_path = self::view_path;
        
        return view(self::view_path.'.create' ,compact('accounts_tree' ,'restriction_number' ,'view_path'));
    }

    function store(DailyRestrictionsReq $req) {
        $data = $req->all();
        $table_data = $data['table_data'];
        unset($data['table_data']);
        $fiscal = FiscalYear::where('status' ,1)->first();
        $data['fiscal_year_id'] = $fiscal->id;
        $restriction_number = date('y') . '0000';
        $last_restriction = Model::withTrashed()->orderBy('id' ,'desc')->first();
        if ($last_restriction) $restriction_number .= ($last_restriction->id + 1);
        else $restriction_number .= '1';
        $data['restriction_number'] = $restriction_number;
        $restriction = Model::create($data);
        foreach($table_data as $index => $row) {
            $table_row = [
                'daily_restriction_id' => $restriction->id,
                'accounts_tree_id' => $row['accounts_tree_id'],
                'debit_amount' => $row['debit_amount'],
                'credit_amount' => $row['credit_amount'],
                'notes' => $row['notes'],
                'account_tree_code' => $row['account_tree_code'],
                'cost_center_code' => $row['cost_center_code'],
                'cost_center_root_id' => $row['cost_center_root_id'],
                'cost_center_id' => $row['cost_center_id'],
            ];
            DailyRestrictionTable::create($table_row);
        }
        return redirect(route('daily-restrictions.index'))
            ->with(['message' => __('accounting-module.daily-restriction-created') ,'alert-type' => 'success']);
    }

    function edit($id) {
        if (!auth()->user()->can('edit_daily-restrictions')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $model = Model::findOrFail($id);
        $accounts_tree = self::get_tree_as_table();
        $view_path = self::view_path;
        $extra_url_params = [
            'name' => 'daily_restriction',
            'value' => $id
        ];
        return view(self::view_path.'.edit' ,compact('accounts_tree' ,'model' ,'view_path' ,'extra_url_params'));
    }

    function update(DailyRestrictionsReq $req ,$id) {
        $data = $req->all();
        $table_data = $data['table_data'];
        unset($data['table_data']);
        $restriction = Model::findOrFail($id);
        $fiscal = FiscalYear::where('status' ,1)->first();
        $data['fiscal_year_id'] = $fiscal->id;
        $restriction->update($data);
        $restriction->my_table()->forceDelete();
        foreach($table_data as $index => $row) {
            $table_row = [
                'daily_restriction_id' => $restriction->id,
                'accounts_tree_id' => $row['accounts_tree_id'],
                'debit_amount' => $row['debit_amount'],
                'credit_amount' => $row['credit_amount'],
                'notes' => $row['notes'],
                'account_tree_code' => $row['account_tree_code'],
                'cost_center_code' => $row['cost_center_code'],
                'cost_center_root_id' => $row['cost_center_root_id'],
                'cost_center_id' => $row['cost_center_id'],
            ];
            DailyRestrictionTable::create($table_row);
        }
        return redirect(route('daily-restrictions.index'))
            ->with(['message' => __('accounting-module.daily-restriction-updated') ,'alert-type' => 'success']);
    }

    function update_for_account_tree(DailyRestrictionsForAccTree $req ,$id) {
        $data = $req->all();
        $table_data = $data['table_data'];
        unset($data['table_data']);
        $restriction = Model::findOrFail($id);
        $restriction->update($data);
        $restriction->my_table()->forceDelete();
        foreach($table_data as $index => $row) {
            $table_row = [
                'daily_restriction_id' => $restriction->id,
                'accounts_tree_id' => $row['accounts_tree_id'],
                'debit_amount' => $row['debit_amount'],
                'credit_amount' => $row['credit_amount'],
                'notes' => $row['notes'],
                'account_tree_code' => $row['account_tree_code'],
                'cost_center_code' => $row['cost_center_code'],
                'cost_center_root_id' => $row['cost_center_root_id'],
                'cost_center_id' => $row['cost_center_id'],
            ];
            DailyRestrictionTable::create($table_row);
        }
        return redirect(route('daily-restrictions.index'))
            ->with('success' ,__('accounting-module.daily-restriction-updated'));
    }

    function delete($id) {

        if (!auth()->user()->can('delete_daily-restrictions')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $model = Model::findOrFail($id);
        if ($model->am_deleteable()) {
            $model->my_table()->delete();
            $model->delete();
            return redirect()->back()->with(['message' => __('accounting-module.daily-restriction-deleted') ,'alert-type' => 'success']);
        }
        return redirect()->back()->with(['message' => __('accounting-module.cant-daily-restriction') ,'alert-type' => 'warning']);
    }

    function print($id) {

        if (!auth()->user()->can('print_daily-restrictions')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $model = Model::with([
            'my_table' => function ($q) {
                $q->with(['account_tree' ,'cost_center']);
            }
        ])->findOrFail($id);
        $code = view(self::view_path.'.print' ,compact('model'))->render();
        return response(['code' => $code]);
    }
    
}

