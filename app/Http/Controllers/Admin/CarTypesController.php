<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\carTypes\carTypesRequest;
use App\Models\CarType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarTypesController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view_car_types');
//        $this->middleware('permission:create_car_types',['only'=>['create','store']]);
//        $this->middleware('permission:update_car_types',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_car_types',['only'=>['destroy','deleteSelected']]);
    }

    public function index()
    {
        if (!auth()->user()->can('view_car_types')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $carTypes = CarType::orderBy('id' ,'desc')->get();
        return view('admin.carTypes.index', compact('carTypes'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_car_types')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.carTypes.create');
    }

    public function store(carTypesRequest $request)
    {
        if (!auth()->user()->can('create_car_types')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        CarType::create($request->all());
        return redirect()->to('admin/carTypes')
            ->with(['message' => __('words.carType-created'), 'alert-type' => 'success']);
    }

    public function edit(CarType  $carType)
    {
        if (!auth()->user()->can('update_car_types')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.carTypes.edit', compact('carType'));
    }

    public function update(carTypesRequest $request, CarType $carType)
    {
        if (!auth()->user()->can('update_car_types')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $carType->update($request->all());
        return redirect()->to('admin/carTypes')
            ->with(['message' => __('words.carType-updated'), 'alert-type' => 'success']);
    }

    public function destroy(CarType $carType)
    {
        if (!auth()->user()->can('delete_car_types')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $carType->delete();
        return redirect()->to('admin/carTypes')
            ->with(['message' => __('words.carType-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_car_types')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            CarType::whereIn('id', $request->ids)->delete();
            return redirect()->to('admin/carTypes')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/carTypes')
            ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }
}
