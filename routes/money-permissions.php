<?php

use Illuminate\Support\Facades\Route;
// money-permissions
Route::group(['namespace' => 'MoneyPermissions'] ,function () {
    Route::group(['prefix' => 'locker-exchanges'] ,function () {
        Route::get('/' ,'LockerExchangeController@index')->name('locker-exchanges.index');
        Route::post('/delete-selected' ,'LockerExchangeController@delete_selected')->name('locker-exchanges.delete_selected');
        Route::delete('/destroy/{id}' ,'LockerExchangeController@destroy')->name('locker-exchanges.destroy');
        Route::get('/edit/{id}' ,'LockerExchangeController@edit')->name('locker-exchanges.edit');
        Route::put('/update/{id}' ,'LockerExchangeController@update')->name('locker-exchanges.update');
        Route::put('/update-to-bank/{id}' ,'LockerExchangeController@updateToBank')->name('locker-exchanges.update-to-bank');
        Route::get('/create' ,'LockerExchangeController@create')->name('locker-exchanges.create');
        Route::post('/create' ,'LockerExchangeController@store')->name('locker-exchanges.store');
        Route::post('/create-to-bank' ,'LockerExchangeController@storeToBank')->name('locker-exchanges.store-to-bank');
        Route::get('/show/{id}' ,'LockerExchangeController@show')->name('locker-exchanges.show');
        Route::get('/approve/{id}' ,'LockerExchangeController@approve')->name('locker-exchanges.approve');
        Route::get('/reject/{id}' ,'LockerExchangeController@reject')->name('locker-exchanges.reject');

        Route::get('/lockers-by-branch' ,'LockerExchangeController@load_branch_lockers')->name('locker-exchanges.lockers-by-branch');
    });

    Route::group(['prefix' => 'locker-receives'] ,function () {
        Route::get('/' ,'LockerReceiveController@index')->name('locker-receives.index');
        Route::post('/delete-selected' ,'LockerReceiveController@delete_selected')->name('locker-receives.delete_selected');
        Route::delete('/destroy/{id}' ,'LockerReceiveController@destroy')->name('locker-receives.destroy');
        Route::get('/edit/{id}' ,'LockerReceiveController@edit')->name('locker-receives.edit');
        Route::put('/update/{id}' ,'LockerReceiveController@update')->name('locker-receives.update');
        Route::get('/create' ,'LockerReceiveController@create')->name('locker-receives.create');
        Route::post('/create' ,'LockerReceiveController@store')->name('locker-receives.store');
        Route::get('/show/{id}' ,'LockerReceiveController@show')->name('locker-receives.show');
        Route::get('/approve/{id}' ,'LockerReceiveController@approve')->name('locker-receives.approve');
        Route::get('/reject/{id}' ,'LockerReceiveController@reject')->name('locker-receives.reject');
    });

    Route::group(['prefix' => 'bank-exchanges'] ,function () {
        Route::get('/' ,'BankExchangeController@index')->name('bank-exchanges.index');
        Route::post('/delete-selected' ,'BankExchangeController@delete_selected')->name('bank-exchanges.delete_selected');
        Route::delete('/destroy/{id}' ,'BankExchangeController@destroy')->name('bank-exchanges.destroy');
        Route::get('/edit/{id}' ,'BankExchangeController@edit')->name('bank-exchanges.edit');
        Route::put('/update/{id}' ,'BankExchangeController@update')->name('bank-exchanges.update');
        Route::put('/update-to-locker/{id}' ,'BankExchangeController@updateToLocker')->name('bank-exchanges.update-to-locker');
        Route::get('/create' ,'BankExchangeController@create')->name('bank-exchanges.create');
        Route::post('/create' ,'BankExchangeController@store')->name('bank-exchanges.store');
        Route::post('/create-to-locker' ,'BankExchangeController@storeToLocker')->name('bank-exchanges.store-to-locker');
        Route::get('/show/{id}' ,'BankExchangeController@show')->name('bank-exchanges.show');
        Route::get('/approve/{id}' ,'BankExchangeController@approve')->name('bank-exchanges.approve');
        Route::get('/reject/{id}' ,'BankExchangeController@reject')->name('bank-exchanges.reject');

        Route::get('/banks-by-branch' ,'BankExchangeController@load_branch_banks')->name('bank-exchanges.banks-by-branch');
    });

    Route::group(['prefix' => 'bank-receives'] ,function () {
        Route::get('/' ,'BankReceiveController@index')->name('bank-receives.index');
        Route::post('/delete-selected' ,'BankReceiveController@delete_selected')->name('bank-receives.delete_selected');
        Route::delete('/destroy/{id}' ,'BankReceiveController@destroy')->name('bank-receives.destroy');
        Route::get('/edit/{id}' ,'BankReceiveController@edit')->name('bank-receives.edit');
        Route::put('/update/{id}' ,'BankReceiveController@update')->name('bank-receives.update');
        Route::get('/create' ,'BankReceiveController@create')->name('bank-receives.create');
        Route::post('/create' ,'BankReceiveController@store')->name('bank-receives.store');
        Route::get('/show/{id}' ,'BankReceiveController@show')->name('bank-receives.show');
        Route::get('/approve/{id}' ,'BankReceiveController@approve')->name('bank-receives.approve');
        Route::get('/reject/{id}' ,'BankReceiveController@reject')->name('bank-receives.reject');
    });
});