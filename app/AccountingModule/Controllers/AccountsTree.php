<?php
namespace App\AccountingModule\Controllers;

use App\Http\Controllers\Controller;
use App\AccountingModule\Models\AccountsTree as Model;
use App\AccountingModule\Models\DailyRestrictionTable;
use App\Http\Requests\AccountingModule\AccountsTreeReq;

class AccountsTree extends Controller {

    const view_path = "accounting-module.accounts-tree";

    function __construct() {
        // $this->middleware('permission:view_accounts-tree-index' ,['except' => ['build_tree_ids_array' ,'build_tree_options' ,'build_tree' ,'run_build_tree']]);
        // $this->middleware('permission:create_accounts-tree-index',['only'=>['createTreeForm','postCreateTreeForm']]);
        // $this->middleware('permission:edit_accounts-tree-index',['only'=>['editTreeForm','postEditTreeForm']]);
        // $this->middleware('permission:delete_accounts-tree-index',['only'=>['destroy' ,'ableToDelete']]);
    }

    function index() {
        if (!auth()->user()->can('view_accounts-tree-index')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $account_tree_ul = $this->run_build_tree();
        $view_path = self::view_path;
        return view(self::view_path.'.index' ,compact('account_tree_ul' ,'view_path'));
    }

    function createTreeForm() {
        if (!auth()->user()->can('create_accounts-tree-index')) {
            return response(['message' => __('accounting-module.not-allowed')] ,400);
        }

        $account_tree_id = isset($_GET['tree_id']) && $_GET['tree_id'] != '' ? $_GET['tree_id'] : NULL;
        if ($account_tree_id == NULL) return response(['message' => __('accounting-module.server-error')] ,400);
        $account = Model::findOrFail($account_tree_id);
        $model = NULL;
        $lang = app()->getLocale();
        $action = route('post-create-tree-form');
        $title = __('accounting-module.create-account-tree-under') .' : '. ($lang == 'ar' ? $account->name_ar : $account->name_en);
        if (auth()->user()->can('accounts-tree-index_account_nature_edit')) {
            $type = NULL;
            $nature = NULL;
        } else {
            $type = $account->custom_type;
            $nature = $account->account_nature;
        }
        return response([
            'html_code' => view(self::view_path.'.form' ,compact('account_tree_id' ,'model' ,'action' ,'title' ,'account' ,'type' ,'nature'))->render()
        ]);
    }

    function postCreateTreeForm(AccountsTreeReq $req) {
        if (!auth()->user()->can('create_accounts-tree-index')) {
            return response(['message' => __('accounting-module.not-allowed')] ,400);
        }
        $data = $req->all();
        if ($data['custom_type'] == 1) {
            $data['account_type_name_en'] = 'Budget';
            $data['account_type_name_ar'] = 'ميزانية';
        } else if ($data['custom_type'] == 2) {
            $data['account_type_name_en'] = 'Income List';
            $data['account_type_name_ar'] = 'قائمة الدخل';
        } else if ($data['custom_type'] == 3) {
            $data['account_type_name_en'] = 'Trading Account';
            $data['account_type_name_ar'] = 'حساب متاجرة';
        } else {
            $data['account_type_name_ar'] = '';
            $data['account_type_name_en'] = '';
        }
        $account_tree = Model::create($data);
        $number_of_siblings = Model::where('accounts_tree_id' ,$account_tree->accounts_tree_id)->count();
        $data_update = ['code' => $req->parent_code.'.'.$number_of_siblings];
        if (!auth()->user()->can('accounts-tree-index_account_nature_edit')) {
            $data_update['custom_type'] = $account_tree->account_tree_parent->custom_type;
            $data_update['account_nature'] = $account_tree->account_tree_parent->account_nature;
            if ($data_update['custom_type'] == 1) {
                $data_update['account_type_name_en'] = 'Budget';
                $data_update['account_type_name_ar'] = 'ميزانية';
            } else if ($data_update['custom_type'] == 2) {
                $data_update['account_type_name_en'] = 'Income List';
                $data_update['account_type_name_ar'] = 'قائمة الدخل';
            } else if ($data_update['custom_type'] == 3) {
                $data_update['account_type_name_en'] = 'Trading Account';
                $data_update['account_type_name_ar'] = 'حساب متاجرة';
            } else {
                $data_update['account_type_name_ar'] = '';
                $data_update['account_type_name_en'] = '';
            }
        }
        $account_tree->update($data_update);
        return response(['message' => __('accounting-module.accounts-tree-created-successfully')]);
    }

    function editTreeForm() {
        if (!auth()->user()->can('edit_accounts-tree-index')) {
            return response(['message' => __('accounting-module.not-allowed')] ,400);
        }

        $account_tree_id = isset($_GET['tree_id']) && $_GET['tree_id'] != '' ? $_GET['tree_id'] : NULL;
        if ($account_tree_id == NULL) return response(['message' => __('accounting-module.server-error')] ,400);
        $model = Model::findOrFail($account_tree_id);
        if (!$model->is_editable()) {
            return response(['message' => __('accounting-module.cant-edit-account-tree')] ,400);
        }
        $account = clone $model;
        $lang = app()->getLocale();
        $title = __('accounting-module.edit-account-tree-under') .' : '. ($lang == 'ar' ? $account->name_ar : $account->name_en);
        $action = route('post-edit-tree-form' ,['tree_id' => $account_tree_id]);
        return response([
            'html_code' => view(self::view_path.'.form' ,compact('account_tree_id' ,'model' ,'action' ,'title' ,'account'))->render()
        ]);
    }

    function postEditTreeForm(AccountsTreeReq $req) {
        if (!auth()->user()->can('edit_accounts-tree-index')) {
            return response(['message' => __('accounting-module.not-allowed')] ,400);
        }
        $model = Model::find($req->id);
        if (!$model->is_editable()) {
            return response(['status' => 400 ,'message' => __('accounting-module.cant-edit-account-tree')] ,400);
        }
        $data = $req->all();
        if ($data['custom_type'] == 1) {
            $data['account_type_name_en'] = 'Budget';
            $data['account_type_name_ar'] = 'ميزانية';
        } else if ($data['custom_type'] == 2) {
            $data['account_type_name_en'] = 'Income List';
            $data['account_type_name_ar'] = 'قائمة الدخل';
        } else if ($data['custom_type'] == 3) {
            $data['account_type_name_en'] = 'Trading Account';
            $data['account_type_name_ar'] = 'حساب متاجرة';
        } else if (!$model->is_type_editable()) {
            if (isset($data['account_type_name_ar'])) unset($data['account_type_name_ar']);
            if (isset($data['account_type_name_en'])) unset($data['account_type_name_en']);
            if (isset($data['custom_type'])) unset($data['custom_type']);
        }
        unset($data['accounts_tree_id']);
        if (!auth()->user()->can('accounts-tree-index_account_nature_edit')) {
            unset($data['custom_type']);
            unset($data['account_nature']);
            unset($data['account_type_name_en']);
            unset($data['account_type_name_ar']);
        }
        $model->update($data);
        return response(['message' => __('accounting-module.accounts-tree-edit-successfully')]);
    }

    function destroy() {
        if (!auth()->user()->can('delete_accounts-tree-index')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $id = isset($_GET['tree_id']) && $_GET['tree_id'] != '' ? $_GET['tree_id'] : NULL;
        if ($id == NULL) return redirect()->back()->with(['message' => __('accounting-module.cant-delete-account-tree') ,'alert-type' => 'warning']);
        $model = Model::find($id);
        if ($model && $model->is_deleteable()) {
            DailyRestrictionTable::with('daily_restriction')->where('accounts_tree_id' ,$id)->orderBy('id' ,'asc')
            ->chunk(50 ,function ($restriction_rows) {
                foreach($restriction_rows as $row) {
                    if ($row->daily_restriction) {
                        $row->daily_restriction->update([
                            'debit_amount' => $row->daily_restriction->debit_amount - $row->debit_amount,
                            'credit_amount' => $row->daily_restriction->credit_amount - $row->credit_amount
                        ]);
                    }
                }
            });
            DailyRestrictionTable::where('accounts_tree_id' ,$id)->delete();
            $model->delete();
            return redirect()->back()->with(['message' => __('accounting-module.accounts-tree-delete-successfully') ,'alert-type' => 'success']);
        }
        return redirect()->back()->with(['message' => __('accounting-module.cant-delete-account-tree') ,'alert-type' => 'warning']);
    }

    function ableToDelete() {
        $id = isset($_GET['tree_id']) && $_GET['tree_id'] != '' ? $_GET['tree_id'] : NULL;
        if (!$id) return response(['message' => __('accounting-module.cant-delete-account-tree')] ,400);
        $model = Model::find($id);
        if (!$model) return response(['message' => __('accounting-module.cant-delete-account-tree')] ,400);
        $has_transaction = DailyRestrictionTable::where('accounts_tree_id' ,$id)->first();
        if ($has_transaction) {
            return response(['status' => 203]);
        }
        return response(['status' => 200]);
    }

    private function run_build_tree() {
        $accounts_tree = Model::where('accounts_tree_id' ,0)->where('tree_level' ,0)->get();
        $html_code = "<ul class='tree'>";
        $count = 1;
        $lang = app()->getLocale();
        foreach($accounts_tree as $acc) {
            $html_code .= '<li> <span class="fa fa-plus clickable" data-current-code="'.$count.'" '.
                'data-current-ul="ul-tree-'.$acc->id.'"></span> <span class="folder-span fa fa-folder"></span> '.
                '<span onclick="select_account_tree('.$acc->id.')"> '.$count.' '.
                ($lang == 'ar' ? $acc->name_ar : $acc->name_en) .'</span><ul style="display:none" id="ul-tree-'.$acc->id.'">';
            $this->build_tree($acc ,$html_code ,$count);
            $html_code .= '</ul></li>';
            $count++;
        }
        $html_code .= "</ul>";
        return $html_code;
    }

	private function build_tree($account, &$htmlCode, $depth, $get_tree_code = NULL) {
        if ($get_tree_code && $get_tree_code->id == $account->id) {
            return $depth;
        }
        $count = 1;
        $lang = app()->getLocale();
		foreach ($account->account_tree_branches as $child) {
            $htmlCode = $htmlCode.'<li> <span class="fa fa-plus clickable" data-current-code="'.$depth.'.'.$count.'" '.
                'data-current-ul="ul-tree-'.$child->id.'"></span> <span class="folder-span fa fa-folder"></span> '.
                '<span onclick="select_account_tree('.$child->id.')"> '.$depth.'.'.$count.' '.
                ($lang == 'ar' ? $child->name_ar : $child->name_en) .'</span>';
			if ($child->account_tree_branches) {
				$htmlCode = $htmlCode.'<ul style="display:none" id="ul-tree-'.$child->id.'">';
				$this->build_tree($child, $htmlCode, $depth.'.'.$count ,$get_tree_code);
				$htmlCode = $htmlCode.'</ul>';
			}
            $htmlCode = $htmlCode.'</li>';
            $count++;
        }
        return $depth.'.'.($count - 1);
    }

	static function build_tree_options($account, &$htmlCode, $selected_id = NULL) {
        $count = 1;
        $lang = app()->getLocale();
		foreach ($account->account_tree_branches as $child) {
            $is_selected = $selected_id == $child->id ? 'selected' : '';
            $child_name = $lang == 'ar' ? $child->name_ar : $child->name_en;
            $htmlCode .= "<option value='$child->id' $is_selected> $child_name </option>";
			if ($child->account_tree_branches) {
				self::build_tree_options($child, $htmlCode, $selected_id);
			}
            $count++;
		}
    }

    static function build_tree_ids_array($account ,&$arr ,$branch_id = NULL) {
        $data =  $account->account_tree_branches()->when($branch_id ,function ($q) use ($branch_id) {
            $q->where('branch_id',$branch_id);
        })->get();
        foreach($data as $child) {
            $arr[] = $child->id;
			if ($child->account_tree_branches) {
				self::build_tree_ids_array($child, $arr ,$branch_id);
			}
        }
    }
    
}

