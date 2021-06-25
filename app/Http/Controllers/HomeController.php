<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    const THEMES = [
        'red' ,'violet' ,'dark-blue' ,'blue' ,'light-blue' ,'green' ,'yellow' ,'orange' ,'chocolate' ,'dark-green'
    ];
    
    public function __construct()
    {
        $this->middleware('auth')->except(['changeAdminTheme' ,'changeCustomerTheme']);
    }

    public function index()
    {
        return view('home');
    }

    function changeAdminTheme($color) {
        if (auth()->check()) {
            $color = in_array($color, self::THEMES) ? $color : 'dark-blue';
            DB::table('users')->where('id', auth()->user()->id)->update(['theme' => $color]);
        }
        return response(['message' => 'changed']);
    }

    function changeCustomerTheme($color) {
        if (auth()->guard('customer')->check()) {
            $color = in_array($color, self::THEMES) ? $color : 'dark-blue';
            DB::table('customers')->where('id', auth()->guard('customer')->user()->id)->update(['theme' => $color]);
        }
        return response(['message' => 'changed']);
    }
}
