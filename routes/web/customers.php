<?php

Route::get('customer/show','CustomerController@show')->name('customer.show');
Route::get('customer/edit','CustomerController@edit')->name('customer.edit');
Route::post('customer/update','CustomerController@update')->name('customer.update');
