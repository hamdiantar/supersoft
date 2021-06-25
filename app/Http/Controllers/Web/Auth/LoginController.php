<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerRequest;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Monolog\Handler\IFTTTHandler;

class LoginController extends Controller
{

    public function loginForm () {

        return view('web.auth.login');
    }

    public function login (Request $request) {

       $this->validate($request, [
          'username'=>'required|string',
          'password'=>'required|string|min:8',
       ]);

        try {

            if (Auth::guard('customer')->attempt(['username' => $request['username'], 'password' => $request['password']])) {

                return redirect(route('web:dashboard'));
            }

            return back()->withInput($request->only('username'));

        }catch (\Exception $e){

            return redirect()->back()
                ->with(['message' => __('sorry, something went Wrong'), 'alert-type' => 'error']);
        }
    }

    public function logout() {

        if (Auth::guard('customer')->check()){

            Auth::guard('customer')->logout();
        }

        return redirect(route('web:login'));
    }

    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleProviderCallback()
    {
        $user = Socialite::driver('facebook')->user();

        $customerRequest = CustomerRequest::where('username', $user->getName())
            ->where('status','pending')
            ->where('provider','facebook')
            ->first();

        if ($customerRequest) {

            return redirect(route('web:login'))->with(['message' => 'request sent, admin will review', 'alert-type' => 'info']);
        }

        $customer = Customer::withoutGlobalScopes()
            ->where('email', $user->getEmail())
            ->where('username', $user->getName())
            ->where('provider','facebook')
            ->first();

        if ($customer) {

            Auth::guard('customer')->loginUsingId($customer->id);
            return redirect(route('web:dashboard'));
        }

        $data = [
            'name'          => $user->getName(),
            'phone'         => rand(),
            'address'       => '',
            'type'          => 'person',
            'branch_id'     => 1,
            'username'      => $user->getName(),
            'password'      => bcrypt('123456aA'),
            'email'         => $user->getEmail(),
            'provider'      => 'facebook',
        ];

        $checkCustomer = Customer::where('username', $user->getName())->count();

        if ($checkCustomer) {

            return redirect(route('web:login'))
                ->with(['message' => __('sorry, this username used before'), 'alert-type' => 'error']);
        }

        CustomerRequest::create($data);

        return redirect(route('web:login'))
            ->with(['message' => __('request sent successfully, admin will review .'), 'alert-type' => 'success']);
    }

    public function redirectToTwitter()
    {

        return Socialite::driver('twitter')->redirect();
    }

    public function handleTwitterCallback()
    {
        $user = Socialite::driver('twitter')->user();

        $customerRequest = CustomerRequest::where('username', $user->getName())
            ->where('status','pending')
            ->where('provider','twitter')
            ->first();

        if ($customerRequest) {

            return redirect(route('web:login'))
                ->with(['message' => __('request sent, admin will review'), 'alert-type' => 'info']);
        }

        $customer = Customer::withoutGlobalScopes()
            ->where('email', $user->getEmail())
            ->where('username', $user->getName())
            ->where('provider','twitter')
            ->first();

        if ($customer) {

            Auth::guard('customer')->loginUsingId($customer->id);
            return redirect(route('web:dashboard'));
        }

        $data = [
            'name'       => $user->getName(),
            'phone'        => rand(),
            'address'       => '',
            'type'          => 'person',
            'branch_id'     => 1,
            'username'      => $user->getName(),
            'password'      => bcrypt('123456aA'),
            'email'         => $user->getEmail(),
            'provider'      => 'twitter',
        ];

        $checkCustomer = Customer::where('username', $user->getName())->count();

        if ($checkCustomer) {

            return redirect(route('web:login'))
                ->with(['message' => __('sorry, this username used before'), 'alert-type' => 'error']);
        }


        CustomerRequest::create($data);

        return redirect(route('web:login'))
            ->with(['message' => __('request sent successfully, admin will review'), 'alert-type' => 'success']);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        $customerRequest = CustomerRequest::where('username', $user->getName())
            ->where('status','pending')
            ->where('provider','google')
            ->first();

        if ($customerRequest) {

            return redirect(route('web:login'))
                ->with(['message' => __('request sent, admin will review .'), 'alert-type' => 'info']);
        }

        $customer = Customer::withoutGlobalScopes()
            ->where('email', $user->getEmail())
            ->where('username', $user->getName())
            ->where('provider','google')
            ->first();

        if ($customer) {

            Auth::guard('customer')->loginUsingId($customer->id);
            return redirect(route('web:dashboard'));
        }

        $data = [
            'name'       => $user->getName(),
            'phone'        => rand(),
            'address'       => '',
            'type'          => 'person',
            'branch_id'     => 1,
            'username'      => $user->getName(),
            'password'      => bcrypt('123456aA'),
            'email'         => $user->getEmail(),
            'provider'      => 'google',
        ];

        $checkCustomer = Customer::where('username', $user->getName())->count();

        if ($checkCustomer) {

            return redirect(route('web:login'))
                ->with(['message' => __('sorry, this username used before'), 'alert-type' => 'error']);
        }


        CustomerRequest::create($data);

        return redirect(route('web:login'))
            ->with(['message' => __('request sent successfully, admin will review .'), 'alert-type' => 'success']);
    }

}
