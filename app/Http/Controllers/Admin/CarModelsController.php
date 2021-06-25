<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CarModels\CarModelsRequest;
use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarModelsController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view_car_models');
//        $this->middleware('permission:create_car_models',['only'=>['create','store']]);
//        $this->middleware('permission:update_car_models',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_car_models',['only'=>['destroy','deleteSelected']]);
    }

    public function index()
    {
        if (!auth()->user()->can('view_car_models')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $carModels = CarModel::orderBy('id' ,'desc')->get();
        return view('admin.carModels.index', compact('carModels'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_car_models')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.carModels.create');
    }

    public function store(CarModelsRequest $request)
    {
        if (!auth()->user()->can('create_car_models')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        CarModel::create($request->all());
        return redirect()->to('admin/carModels')
            ->with(['message' => __('words.carModels-created'), 'alert-type' => 'success']);
    }

    public function edit(CarModel $carModel)
    {
        if (!auth()->user()->can('update_car_models')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.carModels.edit', compact('carModel'));
    }

    public function update(CarModelsRequest $request, CarModel $carModel)
    {
        if (!auth()->user()->can('update_car_models')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $carModel->update($request->all());
        return redirect()->to('admin/carModels')
            ->with(['message' => __('words.carModels-updated'), 'alert-type' => 'success']);
    }

    public function destroy(CarModel $carModel)
    {
        if (!auth()->user()->can('delete_car_models')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $carModel->delete();
        return redirect()->to('admin/carModels')
            ->with(['message' => __('words.carModels-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_car_models')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            CarModel::whereIn('id', $request->ids)->delete();
            return redirect()->to('admin/carModels')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/carModels')
            ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }
}
