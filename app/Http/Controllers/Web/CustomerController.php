<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Web\Customers\UpdateCustomerRequest;
use App\Models\Area;
use App\Models\BankAccount;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\CarType;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\Customer;
use App\Models\CustomerCategory;
use App\Models\CustomerContact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function show() {

        $customer = Auth::guard('customer')->user();
        $companies = Company::all()->pluck('name','id');
        $carsModels = CarModel::all()->pluck('name','id');
        $carsTypes = CarType::all()->pluck('type','id');
        $cars = Car::where('customer_id', $customer->id)->get();

        return view('web.customers.show', compact('customer','companies','carsModels','carsTypes', 'cars'));
    }

    public function edit () {

        $customer = Auth::guard('customer')->user();

        $customerCategories = CustomerCategory::all()->pluck('name','id');

        $countries = Country::all()->pluck('name','id');
        $cities = City::all()->pluck('name','id');
        $areas = Area::all()->pluck('name','id');

        $carsCount = $customer->cars->count();

        return view('web.customers.edit', compact('customer','customerCategories','countries','cities','areas','carsCount'));
    }

    public function update (UpdateCustomerRequest $request) {

        try {

            $customer = Auth::guard('customer')->user();

            $data = $request->all();

            if ($request->has('password')){
                $data['password'] = Hash::make($request['password']);
            }

            if ($request['contacts']) {
                foreach ($request['contacts'] as $contact) {
                    CustomerContact::create([
                        'customer_id' => $customer->id,
                        'phone_1' => $contact['phone_1'],
                        'phone_2' => $contact['phone_2'],
                        'address' => $contact['address'],
                        'name' => $contact['name'],
                    ]);
                }
            }

            if ($request['contactsUpdate']) {
                foreach ($request['contactsUpdate'] as $contactData) {
                    $contact = CustomerContact::find($contactData['contactId']);
                    if ($contact) {
                        $contact->update($contactData);
                    }
                }
            }

            $customer->update($data);
            if ($request['bankAccount']) {
                foreach ($request['bankAccount'] as $bankAccount) {
                    BankAccount::create([
                        'customer_id' => $customer->id,
                        'bank_name' => $bankAccount['bank_name'],
                        'account_name' => $bankAccount['account_name'],
                        'branch' => $bankAccount['branch'],
                        'account_number' => $bankAccount['account_number'],
                        'iban' => $bankAccount['iban'],
                        'swift_code' => $bankAccount['swift_code'],
                    ]);
                }
            }


            if ($request['bankAccountUpdate']) {
                foreach ($request['bankAccountUpdate'] as $bankAccountData) {
                    $bankAccount = BankAccount::find($bankAccountData['bankAccountId']);
                    if ($bankAccount) {
                        $bankAccount->update($bankAccountData);
                    }
                }
            }

        }catch (\Exception $e) {

            dd($e->getMessage());
            return redirect()->back()->with(['message'=> 'sorry, something went wrong', 'alert-type'=>'error']);
        }

        return redirect()->back()->with(['message'=> __('customer updated successfully'), 'alert-type'=>'success']);
    }
}
