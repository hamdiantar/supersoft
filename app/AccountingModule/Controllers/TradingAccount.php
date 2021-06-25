<?php
namespace App\AccountingModule\Controllers;

use App\Scopes\OldBranchScope;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\TradingAccountExport;
use App\AccountingModule\Models\AccountsTree as AccountsTreeModel;

class TradingAccount extends Controller {
    const view_path = "accounting-module.trading-accounts";
    
    protected $filters;

    function index() {
        if (!auth()->user()->can('view_trading-account-index')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        $this->prepare_filters();
        extract($this->filters);
        
        $collections = $this->collection_query_builder($branch_id);

        if (request()->has('form_action'))
            return (
                new ExportPrinterFactory(
                    new TradingAccountExport($collections),
                    request('form_action')
                )
            )();

        return view(self::view_path.'.index' ,[
            'view_path' => self::view_path,
            'collections' => $collections
        ]);
    }

    private function prepare_filters() {
        if (authIsSuperAdmin()) {
            $b_id = isset($_GET['branch_id']) && $_GET['branch_id'] != '' ? $_GET['branch_id'] : NULL;
        } else {
            $b_id = auth()->user()->branch_id;
        }
        $this->filters = [
            'date_from' => isset($_GET['date_from']) && $_GET['date_from'] !=  '' ? $_GET['date_from'] : NULL,
            'date_to' => isset($_GET['date_to']) && $_GET['date_to'] !=  '' ? $_GET['date_to'] : date('m/d/Y'),
            'branch_id' => $b_id
        ];
    }

    private function collection_query_builder($branch_id = NULL) {
        extract($this->filters);
        $data = AccountsTreeModel
        ::withoutGlobalScope(OldBranchScope::class)
        ->leftJoin('daily_restriction_tables' ,'daily_restriction_tables.accounts_tree_id' ,'=' ,'accounts_trees.id')
        ->leftJoin('daily_restrictions' ,'daily_restrictions.id' ,'=' ,'daily_restriction_tables.daily_restriction_id')
        ->where('accounts_trees.accounts_tree_id' ,'!=' ,0)
        ->where('accounts_trees.custom_type' ,3)
        ->when($branch_id ,function ($q) use ($branch_id) {
            $q->where('daily_restrictions.branch_id' ,$branch_id)->where('accounts_trees.branch_id' ,$branch_id);
        })
        ->whereNull('daily_restriction_tables.deleted_at')
        ->whereNull('daily_restrictions.deleted_at')
        ->when($date_from ,function($subQ) use ($date_from ,$date_to) {
            $subQ->where('daily_restrictions.operation_date' ,'>=' ,$date_from)
            ->where('daily_restrictions.operation_date' ,'<=' ,$date_to);
        });
        $lang = app()->getLocale();
        $acc_name_key = 'accounts_trees.'.($lang == 'ar' ? 'name_ar' : 'name_en');
        $_temp = clone $data;
        return [
            'debit_collection' => $data->where('daily_restriction_tables.debit_amount' ,'>' ,0)->select(
                'accounts_trees.id as account_id',
                'accounts_trees.'.($lang == 'ar' ? 'name_ar' : 'name_en').' as account_name',
                'accounts_trees.code as account_code',
                DB::raw('SUM(daily_restriction_tables.debit_amount) as debit_balance')
            )->groupBy('accounts_trees.id' ,'account_nature' ,$acc_name_key ,'accounts_trees.code')->get(),
            'credit_collection' => $_temp->where('daily_restriction_tables.credit_amount' ,'>' ,0)->select(
                'accounts_trees.id as account_id',
                'accounts_trees.'.($lang == 'ar' ? 'name_ar' : 'name_en').' as account_name',
                'accounts_trees.code as account_code',
                DB::raw('SUM(daily_restriction_tables.credit_amount) as credit_balance')
            )->groupBy('accounts_trees.id' ,'account_nature' ,$acc_name_key ,'accounts_trees.code')->get(),
        ];
    }
}