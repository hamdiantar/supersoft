<?php
namespace App\AccountingModule\Controllers;

use App\Http\Controllers\Controller;
use App\AccountingModule\Models\CostCenter as Model;
use App\AccountingModule\Models\DailyRestrictionTable;
use App\Http\Requests\AccountingModule\CostCenterReq;

class CostCenterCont extends Controller {

    const view_path = "accounting-module.cost-centers";

    function __construct() {
        // $this->middleware('permission:view_cost-centers' ,[
        //     'except' => [
        //         'run_build_tree', 'build_tree', 'get_cost_center_branches', 'build_root_centers_options',
        //         'build_centers_options', '_build_centers_options', 'build_centers_ids_array', 'get_my_code' ,'_get_my_code'
        //     ]
        // ]);
        // $this->middleware('permission:create_cost-centers',['only'=>['loadForm','store']]);
        // $this->middleware('permission:edit_cost-centers',['only'=>['loadForm','update']]);
        // $this->middleware('permission:delete_cost-centers',['only'=>['delete' ,'ableToDelete']]);
    }

    function index() {

        if (!auth()->user()->can('view_cost-centers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $account_tree_ul = $this->run_build_tree();
        $view_path = self::view_path;
        return view(self::view_path.'.index' ,compact('account_tree_ul' ,'view_path'));
    }

    function delete() {

        if (!auth()->user()->can('delete_cost-centers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $id = isset($_GET['id']) && $_GET['id'] != '' ?  $_GET['id'] : NULL;
        if ($id) {
            $cost_center = Model::find($id);
            if ($cost_center && !$cost_center->my_branches->first()) {
                DailyRestrictionTable::with('daily_restriction')->where('cost_center_id' ,$id)->orderBy('id' ,'asc')
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
                DailyRestrictionTable::where('cost_center_id' ,$id)->delete();
                $cost_center->delete();
                return redirect()->back()->with(['message' => __('accounting-module.cost-center-deleted') ,'alert-type' => 'success']);
            }
        }
        return redirect()->back()->with(['message' => __('accounting-module.can`t-delete-cost-center') ,'alert-type' => 'error']);
    }

    function ableToDelete() {
        $id = isset($_GET['id']) && $_GET['id'] != '' ? $_GET['id'] : NULL;
        if (!$id) return response(['message' => __('accounting-module.cant-delete-account-tree')] ,400);
        $model = Model::find($id);
        if (!$model) return response(['message' => __('accounting-module.cant-delete-account-tree')] ,400);
        $has_transaction = DailyRestrictionTable::where('cost_center_id' ,$id)->first();
        if ($has_transaction) {
            return response(['status' => 203]);
        }
        return response(['status' => 200]);
    }

    function loadForm($action_for) {
        $id = isset($_GET['id']) && $_GET['id'] != '' ?  $_GET['id'] : NULL;
        $model = $id && $action_for == 'edit' ? Model::findOrFail($id) : NULL;
        $lang = app()->getLocale();
        $title = $model ?
            __('accounting-module.cost-center-editing').' '.($lang == 'ar' ? $model->name_ar : $model->name_en)
            : __('accounting-module.cost-center-creating');
        $tree_level = 0;
        if ($id && $id != 0 && $action_for == 'create') {
            $parent = Model::find($id);
            if ($parent) $tree_level = $parent->tree_level + 1;
        }
        $action = $model ? route('cost-centers.edit') : route('cost-centers.create');
        $html_code = view(self::view_path.'.form' ,compact('id' ,'model' ,'title' ,'action' ,'tree_level'))->render();
        return response(['html_code' => $html_code]);
    }

    function update(CostCenterReq $req) {


        $model = Model::findOrFail($req->id);
        $model->update($req->all());
        return response(['message' => __('accounting-module.cost-centers-updated')]);
    }

    function store(CostCenterReq $req) {

        Model::create($req->all());
        return response(['message' => __('accounting-module.cost-centers-created')]);
    }

    private function run_build_tree() {
        $cost_centers = Model::where('cost_centers_id' ,0)->where('tree_level' ,0)->get();
        $html_code = "<ul class='tree'>";
        $count = 1;
        foreach($cost_centers as $cost_center) {
            $html_code .= view(self::view_path.'.tree-li' ,['child' => $cost_center ,'counting' => $count])->render();
            $html_code .= '<ul style="display:none" id="ul-tree-'.$cost_center->id.'">';
            $this->build_tree($cost_center ,$html_code ,$count);
            $html_code .= '</ul></li>';
            $count++;
        }
        $html_code .= "</ul>";
        return $html_code;
    }

	private function build_tree($cost_center, &$htmlCode, $depth) {
        $count = 1;
		foreach ($cost_center->my_branches as $child) {
            $htmlCode .= view(self::view_path.'.tree-li' ,['child' => $child ,'counting' => $depth. '.' .$count])->render();
			if ($child->my_branches) {
				$htmlCode .= '<ul style="display:none" id="ul-tree-'.$child->id.'">';
				$this->build_tree($child, $htmlCode, $depth.'.'.$count);
				$htmlCode .= '</ul>';
			}
            $htmlCode .= '</li>';
            $count++;
		}
    }

    function get_cost_center_branches() {
        $id = isset($_GET['id']) && $_GET['id'] != '' ? $_GET['id'] : NULL;
        $count = isset($_GET['count']) && $_GET['count'] != '' && $_GET['count'] != 'undefined' ? $_GET['count'] : NULL;
        $selected = isset($_GET['selected']) && $_GET['selected'] != '' ? $_GET['selected'] : NULL;
        $html_options = self::build_centers_options($selected ,$id ,$count);
        return response(['html_options' => $html_options]);
    }

    static function build_root_centers_options($selected_id = NULL ,$branch_id = NULL) {
        $html_options = '<option value=""> '.__('accounting-module.select-one').' </option>';
        $count = 1;
        $lang = app()->getLocale();
        Model::where('cost_centers_id' ,0)->where('tree_level' ,0)->orderBy('id' ,'asc')
        ->when($branch_id ,function ($q) use ($branch_id) {
            $q->where('branch_id' ,$branch_id);
        })
        ->chunk(10 ,function ($centers) use (&$html_options ,$selected_id ,&$count ,$lang) {
            foreach($centers as $cost) {
                $selected = $cost->id == $selected_id ? 'selected' : '';
                $html_options .=
                    '<option data-my-count="'.$count.'" '.$selected.' value="'. $cost->id .'"> '
                        .$count.' '. ($lang == 'ar' ? $cost->name_ar : $cost->name_en) .
                    ' </option>';
                $count++;
            }
        });
        return $html_options;
    }

    static function build_centers_options($selected_id = NULL ,$cost_root_id = NULL ,$count = 1 ,$with_root = false ,$branch_id = NULL) {
        $html_options = '<option value=""> '.__('accounting-module.select-one').' </option>';
        if ($cost_root_id) {
            $cost_center = Model::findOrFail($cost_root_id);
            self::_build_centers_options($cost_center ,$html_options ,$count ,$selected_id);
        } else {
            $lang = app()->getLocale();
            Model::where('cost_centers_id' ,0)->where('tree_level' ,0)->orderBy('id' ,'asc')
            ->when($branch_id ,function ($q) use ($branch_id) {
                $q->where('branch_id' ,$branch_id);
            })
            ->chunk(10 ,function ($centers) use (&$html_options ,$selected_id ,&$count ,$with_root ,$lang ,$branch_id) {
                foreach($centers as $cost) {
                    if ($with_root) {
                        $selected = $cost->id == $selected_id ? 'selected' : '';
                        $html_options .=
                            '<option data-cost-center-code="'.$count.'" '.$selected.' value="'.$cost->id.'">'.
                                $count.' '.($lang == 'ar' ? $cost->name_ar : $cost->name_en).
                            '</option>';
                    }
                    self::_build_centers_options($cost ,$html_options ,$count ,$selected_id ,$branch_id);
                    $count++;
                }
            });
        }
        return $html_options;
    }

    private static function _build_centers_options($cost_center, &$htmlCode, $depth, $selected_id = NULL ,$branch_id = NULL) {
        $count = 1;
        $lang = app()->getLocale();
        $data = $cost_center->my_branches()->when($branch_id ,function ($q) use ($branch_id) {
            $q->where('branch_id' ,$branch_id);
        })->get();
        foreach ($data as $child) {
            $selected = $child->id == $selected_id ? 'selected' : '';
            $htmlCode .=
                '<option data-cost-center-code="'.$depth.'.'.$count.'" '.$selected.' value="'.$child->id.'">'.
                    $depth. '.' .$count.' '.($lang == 'ar' ? $child->name_ar : $child->name_en).
                '</option>';
            if ($child->my_branches) {
                self::_build_centers_options($child, $htmlCode, $depth.'.'.$count, $selected_id);
            }
            $count++;
        }
    }

    static function build_centers_ids_array($cost_center ,&$arr ,$branch_id = NULL) {
        $data = $cost_center->my_branches()->when($branch_id ,function ($q) use ($branch_id) {
            $q->where('branch_id', $branch_id);
        })->get();
        foreach($data as $child) {
            $arr[] = $child->id;
			if ($child->my_branches) {
				self::build_centers_ids_array($child, $arr, $branch_id);
			}
        }
    }

    static function get_my_code($cost_center_id) {
        $cost_center = Model::with('my_parent_cost')->find($cost_center_id);
        if (!$cost_center) return '';
        $current_cost = $cost_center;
        if ($cost_center->cost_centers_id != 0) {
            for($i = $cost_center->tree_level ;$i > 0 ;$i--) {
                $current_cost = $current_cost->my_parent_cost;
            }
        }
        $count = 0;
        Model::where('cost_centers_id' ,0)->where('tree_level' ,0)->orderBy('id' ,'asc')
        ->chunk(50 ,function ($centers) use (&$count ,$current_cost) {
            foreach($centers as $center) {
                $count++;
                if ($center->id == $current_cost->id) return;
            }
        });
        self::_get_my_code($current_cost ,$count ,$cost_center_id);
        return $count;
        
    }

    private static function _get_my_code($cost_center, &$depth, $stop_at_id) {
        $count = 1;
        $complete = true;
        foreach ($cost_center->my_branches as $child) {
            if ($child->id == $stop_at_id) $complete = false;
            if ($complete && $child->my_branches) {
                $depth = $depth.'.'.$count;
                self::_get_my_code($child, $depth, $stop_at_id);
            }
            if (!$complete) {
                $depth = $depth.'.'.$count;
            }
            $count++;
        }
    }
    
}

