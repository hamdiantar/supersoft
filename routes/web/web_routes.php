<?php

use Illuminate\Support\Facades\Auth;

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => [
    'localeSessionRedirect',
    'localizationRedirect',
    'localeViewPath']
], function () {


    Route::group(['middleware' => [], 'as' => 'web:', 'namespace' => 'Web\Auth'], function () {

        Route::get('login','LoginController@loginForm')->name('login.form');
        Route::post('login','LoginController@login')->name('login');

        Route::post('logout','LoginController@logout')->name('logout');


        Route::get('register','RegisterController@registerForm')->name('register.form');
        Route::post('register','RegisterController@register')->name('register');


        Route::get('login/facebook', 'LoginController@redirectToProvider')->name('login.facebook');
        Route::get('login/facebook/callback', 'LoginController@handleProviderCallback')->name('facebook.callback');

        Route::get('login/twitter', 'LoginController@redirectToTwitter')->name('login.twitter');
        Route::get('login/twitter/callback', 'LoginController@handleTwitterCallback')->name('twitter.callback');

        Route::get('login/google', 'LoginController@redirectToGoogle')->name('login.google');
        Route::get('login/google/callback', 'LoginController@handleGoogleCallback')->name('google.callback');;

    });

    Route::group(['middleware' => ['web','CheckWebAuth'], 'as' => 'web:', 'namespace' => 'Web'], function () {

        include 'dashboard.php';
        include 'customers.php';
        include 'cars.php';
        include 'quotations.php';
        include 'sales_invoices.php';
        include 'sales_invoice_return.php';
        include 'work_cards.php';
        include 'notifications.php';
        include 'reservations.php';
        include 'banks_account.php';
        include 'customer_contacts.php';
    });

});

Route::get('customer-color/{color}' ,'HomeController@changeCustomerTheme')->name('customer-color');
