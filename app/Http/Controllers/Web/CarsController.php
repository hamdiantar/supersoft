<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Web\Customers\AddCarRequest;
use App\Http\Requests\Web\Customers\UpdateCarRequest;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\CarType;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CarsController extends Controller
{
    public function store(AddCarRequest  $request) {

        try {

            $data = $request->validated();

            $data['customer_id'] = Auth::guard('customer')->id();

            if ($request->has('image')) {

                $data['image'] = uploadImage($request['image'], 'cars');
            }

            $car = Car::create($data);

        }catch (\Exception $e){

            return redirect()->back()->with(['message'=> __('something went wrong'), 'alert-type'=>'error']);
        }

        return redirect()->back()->with(['message'=> __('car saved successfully'), 'alert-type'=>'success']);
    }

    public function edit (Request $request) {

       $car = Car::find($request['car_id']);

        $companies = Company::all()->pluck('name','id');
        $carsModels = CarModel::where('company_id', $car->company_id)->get()->pluck('name','id');
        $carsTypes = CarType::all()->pluck('type','id');

       $view = view('web.customers.ajax_cars_edit_form', compact('car', 'companies', 'carsTypes', 'carsModels'))->render();

       return response()->json(['view'=> $view], 200) ;
    }

    public function update (UpdateCarRequest $request, Car $car) {

        try {

            $data = $request->validated();

            if ($request->has('image')) {

                $data['image'] = uploadImage($request['image'], 'cars');
            }

            $car->update($data);

        }catch (\Exception $e){

            return redirect()->back()->with(['message'=> __('something went wrong'), 'alert-type'=>'error']);
        }

        return redirect()->back()->with(['message'=> __('car saved successfully'), 'alert-type'=>'success']);

    }

    public function destroy (Car $car) {

        $car->delete();
        return redirect()->back()->with(['message'=> __('car deleted successfully'), 'alert-type'=>'success']);
    }

    public function getCarModel(Request $request)
    {

        try {

            $models = CarModel::where('company_id', $request['company_id'])->get();

            return response()->json(['models' => $models], 200);

        } catch (\Exception $e) {

            return response()->json(['sorry, please try again'], 400);
        }
    }
}
