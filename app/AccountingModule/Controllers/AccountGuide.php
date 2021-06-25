<?php
namespace App\AccountingModule\Controllers;

use App\Http\Controllers\Controller;
use App\AccountingModule\Models\AccountsTree;

class AccountGuide extends Controller {

    const view_path = "accounting-module.account-guide";

    function __construct() {
        // $this->middleware('permission:view_account-guide-index' ,['except' => ['show_message']]);
    }

    function index() {
        if (!auth()->user()->can('view_account-guide-index')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view(self::view_path.'.index');
    }
    
    static function get_tree_as_table() {
        $accounts_tree = AccountsTree::where('accounts_tree_id' ,0)->where('tree_level' ,0)->get();
        $html_code = "";
        $count = 1;
        $type_key = 'account_type_name_ar';
        $name_key = 'name_ar';
        if (app()->getLocale() != 'ar') {
            $type_key = 'account_type_name_en';
            $name_key = 'name_en';
        }
        foreach($accounts_tree as $acc) {
            $type = $acc->$type_key;
            $name = $acc->$name_key;
            $tr = "<tr class='parent-tr'>";
            $tr .= "<td> $type </td>";
            $tr .= "<td> $name </td>";
            $tr .= "<td> $count </td>";
            $tr .= "</tr>";
            $html_code = $html_code.$tr;
            self::build_tree($acc ,$html_code ,$count ,$type_key ,$name_key);
            $count++;
        }
        return $html_code;
    }

    private static function build_tree($account, &$htmlCode, $depth ,$type_key ,$name_key) {
        $count = 1;
		foreach ($account->account_tree_branches as $child) {
            $type = $child->$type_key;
            $name = $child->$name_key;
            $tr = "<tr>";
            $tr .= "<td> $type </td>";
            $tr .= "<td> $name </td>";
            $tr .= "<td> $depth.$count </td>";
            $tr .= "</tr>";
            $htmlCode = $htmlCode.$tr;
			if ($child->account_tree_branches) {
				self::build_tree($child, $htmlCode, $depth.'.'.$count ,$type_key ,$name_key);
			}
            $count++;
		}
    }
    
    function show_message() {
        $message = isset($_GET['message']) && $_GET['message'] != '' ? $_GET['message'] : NULL;
        if ($message) return redirect()->back()->with(['message' => $message, 'alert-type' => 'success']);
        return redirect()->back();
    }
}

