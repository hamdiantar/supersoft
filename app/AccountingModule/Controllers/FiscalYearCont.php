<?php
namespace App\AccountingModule\Controllers;

use App\Http\Controllers\Controller;
use App\AccountingModule\Models\FiscalYear as Model;
use App\Http\Requests\AccountingModule\FiscalYearReq;

class FiscalYearCont extends Controller {

    const view_path = "accounting-module.fiscal-years";

    function __construct() {
        // $this->middleware('permission:view_fiscal-years' ,['except' => ['check_period' ,'check_period_availability' ,'check_available_date']]);
        // $this->middleware('permission:create_fiscal-years',['only'=>['create','store']]);
        // $this->middleware('permission:edit_fiscal-years',['only'=>['edit','update' ,'changeStatus']]);
        // $this->middleware('permission:delete_fiscal-years',['only'=>['delete']]);
    }

    function index() {
        if (!auth()->user()->can('view_fiscal-years')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        $collection = Model::orderBy('id' ,'desc');
        $view_path = self::view_path;
        return view(self::view_path.'.index' ,compact('collection' ,'view_path'));
    }

    function changeStatus($lang ,$id ,$is_internal_use = false) {
        if (!auth()->user()->can('edit_fiscal-years')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        Model::where('status' ,1)->update(['status' => 0]);
        Model::findOrFail($id)->update(['status' => 1]);
        return $is_internal_use ? : response(['status' => 200]);
    }

    function delete($id) {
        if (!auth()->user()->can('delete_fiscal-years')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        $model = Model::findOrFail($id);
        if ($model->daily_restrictions->first()) {
            return redirect()->back()->with(['message' => __('accounting-module.fiscal-years-has-data') ,'alert-type' => 'warning']);
        }
        $model->delete();
        return redirect()->back()->with(['message' => __('accounting-module.fiscal-years-deleted') ,'alert-type' => 'warning']);
    }

    function create() {
        if (!auth()->user()->can('create_fiscal-years')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        return view(self::view_path.'.create');
    }

    function store(FiscalYearReq $req) {
        if (!auth()->user()->can('create_fiscal-years')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        if ($this->check_period($req->start_date ,$req->end_date)) {
            return redirect()->back()->withInput()->with(['message' => __('accounting-module.start-date-greater') ,'alert-type' => 'warning']);
        }
        if($this->check_period_availability($req->start_date ,$req->end_date)) {
            return redirect()->back()->withInput()->with(['message' => __('accounting-module.fiscal-years-exists') ,'alert-type' => 'warning']);
        }
        $model = Model::create($req->all());
        if ($req->has('status') && $req->status == 1) {
            $lang = app()->getLocale();
            $this->changeStatus($lang ,$model->id ,true);
        }
        return redirect(route('fiscal-years.index'))->with(['message' => __('accounting-module.fiscal-years-created') ,'alert-type' => 'success']);
    }

    function edit($id) {
        if (!auth()->user()->can('edit_fiscal-years')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        $model = Model::findOrFail($id);
        if ($model->daily_restrictions->first()) {
            return redirect()->back()->with(['message' => __('accounting-module.fiscal-years-has-data') ,'alert-type' => 'warning']);
        }
        $extra_url_params = [
            'name' => 'fiscal_year',
            'value' => $id
        ];
        return view(self::view_path.'.edit' ,compact('model' ,'extra_url_params'));
    }

    function update(FiscalYearReq $req ,$id) {
        if (!auth()->user()->can('edit_fiscal-years')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        if ($this->check_period($req->start_date ,$req->end_date)) {
            return redirect()->back()->withInput()->with(['message' => __('accounting-module.start-date-greater') ,'alert-type' => 'warning']);
        }
        $model = Model::findOrFail($id);
        if ($model->daily_restrictions->first()) {
            return redirect()->back()->with('warning' ,__('accounting-module.fiscal-years-has-data'));
        }
        if($this->check_period_availability($req->start_date ,$req->end_date ,$id)) {
            return redirect()->back()->withInput()->with(['message' => __('accounting-module.fiscal-years-exists') ,'alert-type' => 'warning']);
        }
        $model->update($req->all());
        if ($req->has('status') && $req->status == 1) {
            $lang = app()->getLocale();
            $this->changeStatus($lang ,$model->id ,true);
        }
        return redirect(route('fiscal-years.index'))->with(['message' => __('accounting-module.fiscal-years-updated') ,'alert-type' => 'success']);
    }

    private function check_period($start ,$end) {
        return $start > $end;
    }

    private function check_period_availability($start ,$end ,$id = NULL) {
        $start_exists =
            Model::where('start_date' ,'<=' ,$start)
            ->where('end_date' ,'>=' ,$start)
            ->when($id ,function ($q) use ($id) {
                $q->where('id' ,'!=' ,$id);
            })
            ->first();
        $end_exists =
            Model::where('start_date' ,'<=' ,$end)
            ->where('end_date' ,'>=' ,$end)
            ->when($id ,function ($q) use ($id) {
                $q->where('id' ,'!=' ,$id);
            })
            ->first();
        return $start_exists || $end_exists;
    }

    function check_available_date() {
        $date = isset($_GET['date']) && $_GET['date'] != '' ? $_GET['date'] : NULL;
        $year = Model::where('status' ,1)->first();
        if ($date && $year) {
            if ($date >= $year->start_date && $date <= $year->end_date) {
                return response(['status' => 200]);
            }
        }
        if ($year) {
            return response([
                'message' =>
                    __('accounting-module.fiscal-years-is-wrong').' ,'.
                    __('accounting-module.fiscal-year-available') .' '.
                    __('accounting-module.from') .' '.$year->start_date.' '.
                    __('accounting-module.to'). ' '.$year->end_date ,
                'status' => 203
            ]);
        }
        return response(['message' => __('accounting-module.fiscal-years-is-not-set') ,'status' => 203]);
    }

}
