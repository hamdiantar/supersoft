<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => [
    'localeSessionRedirect',
    'localizationRedirect',
    'localeViewPath']
], function () {
    Route::group(['middleware' => ['web','CheckAdmin','auth'] ,'prefix' => 'opening-balance'], function () {
//        Route::get('/' ,'OpeningBalanceController@index')->name('opening-balance.index');
//        Route::get('create' ,'OpeningBalanceController@create')->name('opening-balance.create');
//        Route::get('show/{openingBalance}' ,'OpeningBalanceController@show')->name('opening-balance.show');
//        Route::post('create' ,'OpeningBalanceController@store')->name('opening-balance.store');
//        Route::post('deleteSelected' ,'OpeningBalanceController@deleteSelected')->name('opening-balance.deleteSelected');
//        Route::get('edit/{id}' ,'OpeningBalanceController@edit')->name('opening-balance.edit');
//        Route::post('edit/{id}' ,'OpeningBalanceController@update')->name('opening-balance.update');
//        Route::get('delete/{id}' ,'OpeningBalanceController@delete')->name('opening-balance.delete');

        Route::get('build-row' ,'OpeningBalanceController@buildRow')->name('opening-balance.build-row');
        Route::get('get-main-types' ,'OpeningBalanceController@getMainTypes')->name('opening-balance.get-main-types');
        Route::get('get-sub-types' ,'OpeningBalanceController@getSubTypes')->name('opening-balance.get-sub-types');
        Route::get('get-parts' ,'OpeningBalanceController@getParts')->name('opening-balance.get-parts');

//        Route::get('created' ,'OpeningBalanceController@redirect_created')->name('opening-balance.created');
//        Route::get('edited' ,'OpeningBalanceController@redirect_updated')->name('opening-balance.edited');
    });
});

//NEW OPENING BALANCE
Route::resource('opening-balance', 'OpeningBalanceController');
Route::post('opening-balance/deleteSelected' ,'OpeningBalanceController@deleteSelected')->name('opening-balance.deleteSelected');

//AJAX
Route::post('opening-balance/select-part', 'OpeningBalanceController@selectPartRaw')->name('opening-balance.select.part');
Route::post('opening-balance/price-segments', 'OpeningBalanceController@priceSegments')->name('opening-balance.price.segments');
