<?php

namespace App\Filters;

use App\Models\Car;
use App\Models\Customer;
use App\Models\ExpensesItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CustomerCarFilter
{
    public function filter(Request $request): Builder
    {
        return Customer::where(function ($query) use ($request) {
            if ($request->has('name') && $request->name != '' && $request->name != null) {
                $query->where('name_ar', 'like', '%' . $request->name . '%')
                    ->orWhere('name_en', 'like', '%' . $request->name . '%');
            }

            if ($request->has('type') && $request->type != '' && $request->type != null) {
                $query->where('type', $request->type);
            }

            if ($request->has('responsible') && $request->responsible != '' && $request->responsible != null) {
                $query->where('responsible', $request->responsible);
            }

            if ($request->has('phone') && $request->phone != '' && $request->phone != null) {
                $query->where('phone1', $request->phone)
                ->orWhere('phone2', $request->phone);
            }

            if ($request->has('customer_category_id') && $request->customer_category_id != '' && $request->customer_category_id != null) {
                $query->where('customer_category_id', $request->customer_category_id);
            }

            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->has('plate_number') && $request->plate_number != '' && $request->plate_number != null) {
                $car = Car::where('plate_number', $request->plate_number)->first();
                $query->where('id', $car->customer_id);
            }

            if ($request->has('model') && $request->model != '' && $request->model != null) {
                $car = Car::where('model', $request->model)->first();
                $query->where('id', $car->customer_id);
            }

            if ($request->has('Chassis_number') && $request->Chassis_number != '' && $request->Chassis_number != null) {
                $car = Car::where('Chassis_number', $request->Chassis_number)->first();
                $query->where('id', $car->customer_id);
            }

            if ($request->has('car_type') && $request->car_type != '' && $request->car_type != null) {
                $car = Car::where('type', $request->car_type)->first();
                $query->where('id', $car->customer_id);
            }
        });
    }
}
