<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cars\AddCarRequest;
use App\Http\Requests\Admin\Cars\UpdateCarRequest;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\CarType;
use App\Models\Company;
use App\Models\Customer;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class CarsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Car::where('customer_id', request()->segment(4))->with('carType')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('carType', function ($row) {
                    if ($row->carType) {
                        return $row->carType->type_ar;
                    }
                    return '---';
                })
                ->addColumn('action', function ($row) {
                    $btn = "<a type='button' style='cursor:pointer' data-info='$row->id,$row->type_id,$row->model_id,$row->plate_number,$row->Chassis_number,$row->speedometer,$row->barcode,$row->color,$row->image,$row->motor_number,$row->company_id'
                  id='editCar'><i class='fa fa-edit fa-2x' style='color:cornflowerblue'></i></a>";
                    $btn = $btn . "<a type='button' id='removeCar'  data-id=$row->id style='cursor:pointer'><i class='fa fa-trash fa-2x'  style='color:#F44336'></i></a>";

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $customer = Customer::find(request()->segment(4));
        return view('admin.customers.parts.add_car', compact('customer'));
    }

    public function indexModal(Request $request)
    {
        $data = Car::where('customer_id', $request->customer_id)->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('carType', function ($row) {
                if ($row->carType) {
                    return $row->carType->type_ar;
                }
                return '---';
            })
            ->addColumn('action', function ($row) {
                $btn = "<a type='button' style='cursor:pointer' data-info='$row->id,$row->type_id,$row->model_id,$row->plate_number,$row->Chassis_number,$row->speedometer,$row->barcode,$row->color,$row->image,$row->motor_number,$row->company_id'
                  id='editCar'><i class='fa fa-edit fa-2x' style='color:cornflowerblue'></i></a>";
                $btn = $btn . "<a type='button' id='removeCar'  data-id=$row->id style='cursor:pointer'><i class='fa fa-trash fa-2x'  style='color:#F44336'></i></a>";

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function AddCar(Request $request)
    {
        $validationRules = [
            'customer_id' => 'required|integer|exists:customers,id',
            'plate_number' => 'required|string',
//            'barcode'      => 'nullable|string|unique:cars',
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        if ($request->has('car_id') && $request->car_id !== null) {
            $image = $this->getOldImage($request->car_id);
        }
        $car = Car::updateOrCreate(['id' => $request->car_id], [
            'type_id' => $request->type_id,
            'model_id' => $request->model_id,
            'company_id' => $request->company_id,
            'plate_number' => $request->plate_number,
            'Chassis_number' => $request->Chassis_number ?? null,
            'speedometer' => $request->speedometer ?? null,
            'barcode' => $request->barcode ?? null,
            'color' => $request->color ?? null,
            'image' => $request->has('image') && $request->image !== null ? uploadImage($request->image, 'cars') : ($image ?? null),
            'customer_id' => $request->customer_id,
            'motor_number' => $request->motor_number,
        ]);
        $customer = Customer::find($request->customer_id);
        $carsCount = $customer->cars()->count();

        return response()->json(['carsCount' => $carsCount]);
    }

    public function removeCar($id)
    {
        $car = Car::find($id);
        if (File::exists(storage_path('app/public/images/cars/' . $car->image))) {
            File::delete(storage_path('app/public/images/cars/' . $car->image));
        }
        $customer = Customer::find($car->customer_id);
        $car->delete();
        $carsCount = $customer->cars()->count();
        return response()->json(['carsCount' => $carsCount]);
    }

    public function printBarcode(Request $request)
    {

        $validator = Validator::make($request->all(), [
//            'qty' => 'required|integer|min:1',
            'id' => 'required|integer|exists:cars,id',
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors()->first()], 400);
        }

//        $qty = $request['qty'];
        $qty = 1;

        $car = Car::find($request['id']);

        $barcode_value = $car->barcode;

        if (!$barcode_value) {
            return  response()->json(__('Barcode is empty'), 400);
        }

        $barcode = new BarcodeGenerator();



        return view('admin.customers.parts.barcode', compact('barcode', 'barcode_value', 'qty'));
    }

    public function getOldImage(int $carId)
    {
        $car = Car::find($carId);
        return $car->image;
    }

//  NEW VERSION OF ADD CAR TO CUSTOMER

    public function show(Customer $customer)
    {
        $cars = $customer->cars;

        $companies = Company::all()->pluck('name', 'id');
//        $carsModels = CarModel::all()->pluck('name', 'id');
        $carsTypes = CarType::all()->pluck('type', 'id');

        return view('admin.customers.show', compact('customer', 'cars', 'companies', 'carsTypes'));
    }

    public function store(AddCarRequest $request)
    {
        try {

            $data = $request->validated();

            $data['customer_id'] = $request['customer_id'];

            if ($request->has('image')) {

                $data['image'] = uploadImage($request['image'], 'cars');
            }

            $car = Car::create($data);

        } catch (\Exception $e) {

            return redirect()->back()->with(['message' => __('something went wrong'), 'alert-type' => 'error']);
        }

        return redirect()->back()->with(['message' => __('car saved successfully'), 'alert-type' => 'success']);
    }

    public function edit(Request $request)
    {

        $car = Car::find($request['car_id']);

        $companies = Company::all()->pluck('name', 'id');
        $carsModels = CarModel::where('company_id', $car->company_id)->get()->pluck('name', 'id');
        $carsTypes = CarType::all()->pluck('type', 'id');

        $view = view('admin.customers.cars.ajax_cars_edit_form', compact('car', 'companies', 'carsTypes', 'carsModels'))->render();

        return response()->json(['view' => $view], 200);
    }

    public function update(UpdateCarRequest $request, Car $car)
    {
        try {

            $data = $request->validated();

            if ($request->has('image')) {

                $data['image'] = uploadImage($request['image'], 'cars');
            }

            $car->update($data);

        } catch (\Exception $e) {

            return redirect()->back()->with(['message' => __('something went wrong'), 'alert-type' => 'error']);
        }

        return redirect()->back()->with(['message' => __('car saved successfully'), 'alert-type' => 'success']);

    }

    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->back()->with(['message' => __('car deleted successfully'), 'alert-type' => 'success']);
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
