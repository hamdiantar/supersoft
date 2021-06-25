<?php

Route::get('customers-requests','CustomerRequestController@index')->name('customers.requests.index');
Route::post('customers-requests/accept','CustomerRequestController@accept')->name('customers.requests.accept');
Route::post('customers-requests/reject','CustomerRequestController@reject')->name('customers.requests.reject');
Route::delete('customers-requests/{customerRequest}/delete','CustomerRequestController@destroy')->name('customers.requests.destroy');
Route::post('customers-requests/delete-selected','CustomerRequestController@deleteSelected')->name('customers.requests.delete.selected');
