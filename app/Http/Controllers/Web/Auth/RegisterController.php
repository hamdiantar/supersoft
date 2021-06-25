<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\RegisterRequest;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\CustomerRequest;
use App\Models\User;
use App\Notifications\RegisterCustomersNotifications;
use App\Services\MailServices;
use App\Services\NotificationServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{

    use NotificationServices;

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    public function registerForm () {

        $branches = Branch::all()->pluck('name','id');

        return view('web.auth.register', compact('branches'));
    }

    public function register (RegisterRequest $request) {

        try {

            $checkCustomer = Customer::where('username', $request['username'])->count();

            if ($checkCustomer) {

                return back()->withInput($request->except('password'))
                    ->with(['message' => __('sorry, this username used before'), 'alert-type' => 'error']);
            }

            $checkCustomerPhone = Customer::where('phone1', $request['phone'])->count();

            if ($checkCustomerPhone) {

                return back()->withInput($request->except('password'))
                    ->with(['message' => __('sorry, this phone used before'), 'alert-type' => 'error']);
            }

            $data = $request->validated();

            $data['password'] = Hash::make($request['password']);

            $customerRequest = CustomerRequest::create($data);

        }catch (\Exception $e) {

            return redirect()->back()
                ->with(['message' => __('sorry, something went Wrong'), 'alert-type' => 'error']);

        }

        try {

            $this->sendNotification('customer_request','user', ['customer_request' => $customerRequest]);

        }catch (\Exception $e) {

            return redirect()->back()
                ->with(['message' => __('request sent successfully, admin will review .'), 'alert-type' => 'success']);
        }

        return redirect()->back()
            ->with(['message' => __('request sent successfully, admin will review .'), 'alert-type' => 'success']);
    }
}
