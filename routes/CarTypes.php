<?php
Route::get('/carTypes', 'CarTypesController@index')->name('carTypes.index');
Route::get('/carTypes/create', 'CarTypesController@create')->name('carTypes.create');
Route::post('/carTypes/store', 'CarTypesController@store')->name('carTypes.store');
Route::get('/carTypes/edit/{carType}', 'CarTypesController@edit')->name('carTypes.edit');
Route::put('/carTypes/{carType}', 'CarTypesController@update')->name('carTypes.update');
Route::delete('/carTypes/delete/{carType}', 'CarTypesController@destroy')->name('carTypes.destroy');
Route::post('/carTypes/delete-selected', 'CarTypesController@deleteSelected')->name('carTypes.deleteSelected');
