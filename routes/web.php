<?php

use App\Models\User;
use App\Notifications\RegisterCustomersNotifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

//Route::get('/', function () {
//    return view('welcome');
//});


//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

///// Begin admin routes ////
include ('admin.php');
///// End admin routes /////
Route::get('admin-color/{color}' ,'HomeController@changeAdminTheme')->name('admin-color');


///// Begin admin routes ////
include ('web/web_routes.php');
///// End admin routes /////


Route::get('test', function () {

//    $users = User::find(1);
//    Notification::send($users, new \App\Notifications\LessPartsNotifications());

//    event(new App\Events\StatusLiked('slam ali'));
    return "Event has been sent!";
});


Route::get('test_2', function () {

    return view('test');
});
