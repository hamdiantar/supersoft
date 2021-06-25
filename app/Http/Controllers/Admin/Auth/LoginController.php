<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function logout(Request $request)
    {
        activity()
            ->performedOn(auth()->user())
            ->causedBy(auth()->user())
            ->withProperties(['logout date' => Carbon::now()])
            ->log(__('user').' '. auth()->user()->name . ' ' . __('logout date').' '. Carbon::now() );

        $this->guard()->logout();

//        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect(route('admin:login'))
            ->with(['message'=>__('thank you for your time'),'alert-type'=>'success']);
    }

    protected function authenticated(Request $request, $user)
    {
        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties(['login date' => Carbon::now()])
            ->log(__('user').' '. $user->name . ' ' . __('login date').' '. Carbon::now() );

        return redirect(route('admin:home'))
            ->with(['message'=>'welcome ' . auth()->user()->name,'alert-type'=>'success']);
    }

    public function username()
    {
        return 'username';
    }

    protected function credentials(Request $request)
    {
        return  array_merge($request->only($this->username(), 'password'), ['status' => 1]);
    }
}
