<?php

namespace App\Http\Controllers\Admin;

use App\Model\CapitalBalance as Model;
use App\Http\Requests\CapitalBalanceRequest as Request;
use App\Http\Controllers\Controller;

class CapitalBalanceController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view_capital-balance');
//        $this->middleware('permission:create_capital-balance',['only'=>['create','store']]);
//        $this->middleware('permission:update_capital-balance',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_capital-balance',['only'=>['destroy','deleteSelected']]);
    }

    const view = "admin.capital-balance.";
    const module_base = "admin:capital-balance.index";

    public function index() {

        if (!auth()->user()->can('view_capital-balance')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branch = isset($_GET['branch']) && $_GET['branch'] != '' ? $_GET['branch'] : NULL;
        $id = isset($_GET['id']) && $_GET['id'] != '' ? $_GET['id'] : NULL;
        $balances =
            Model::when($id ,function ($q) use ($id) {
                $q->where('id' ,$id);
            })
            ->when($branch ,function ($q) use ($branch) {
                $q->where('branch_id' ,$branch);
            })
            ->orderBy('id' ,'desc')
            ->get();
        return view(self::view . 'index' ,compact('balances'));
    }

    public function create() {

        if (!auth()->user()->can('create_capital-balance')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches_have_balance = Model::pluck('branch_id')->toArray();
        $branches = \App\Models\Branch::whereNotIn('id' ,$branches_have_balance)->get();
        if (count($branches) <= 0) {
            return redirect(route(self::module_base))->with([
                'message' => __('words.no-capital-balance-to-add'),
                'alert-type' => 'warning'
            ]);
        }
        return view(self::view . 'create' ,compact('branches'));
    }

    public function store(Request $request) {

        if (!auth()->user()->can('create_capital-balance')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        Model::create($request->all());
        return redirect(route(self::module_base))->with([
            'message' => __('words.balance-created'),
            'alert-type' => 'success'
        ]);
    }

    public function show(Model $capitalBalance) {

        if (!auth()->user()->can('view_capital-balance')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view(self::view . 'show' ,compact('capitalBalance'));
    }

    public function edit(Model $capitalBalance) {

        if (!auth()->user()->can('update_capital-balance')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ( !$capitalBalance->editable() )
            return redirect(route(self::module_base))->with([
                'message' => __('words.balance-ineditable'),
                'alert-type' => 'warning'
            ]);
        $branches = \App\Models\Branch::all();
        return view(self::view . 'edit' ,compact('capitalBalance' ,'branches'));
    }

    public function update(Request $request, Model $capitalBalance) {

        if (!auth()->user()->can('update_capital-balance')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ( !$capitalBalance->editable() )
            return redirect(route(self::module_base))->with([
                'message' => __('words.balance-ineditable'),
                'alert-type' => 'warning'
            ]);
        $capitalBalance->update($request->all());
        return redirect(route(self::module_base))->with([
            'message' => __('words.balance-updated'),
            'alert-type' => 'success'
        ]);
    }

    public function destroy(Model $capitalBalance) {

        if (!auth()->user()->can('delete_capital-balance')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $capitalBalance->delete();
        return redirect(route(self::module_base))->with([
            'message' => __('words.balance-deleted'),
            'alert-type' => 'success'
        ]);
    }

    public function deleteSelected() {

        if (!auth()->user()->can('delete_capital-balance')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (!request()->has('ids')) {
            return redirect(route(self::module_base))->with([
                'message' => __('words.select-balance-at-least'),
                'alert-type' => 'error'
            ]);
        }
        foreach(array_unique(request('ids')) as $id) {
            $balance = Model::find($id);
            if ($balance) $balance->delete();
        }
        return redirect(route(self::module_base))->with([
            'message' => __('words.balance-deleted'),
            'alert-type' => 'success'
        ]);
    }

}
