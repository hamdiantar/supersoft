<?php
Route::get('/carModels', 'CarModelsController@index')->name('carModels.index');
Route::get('/carModels/create', 'CarModelsController@create')->name('carModels.create');
Route::post('/carModels/store', 'CarModelsController@store')->name('carModels.store');
Route::get('/carModels/edit/{carModel}', 'CarModelsController@edit')->name('carModels.edit');
Route::put('/carModels/{carModel}', 'CarModelsController@update')->name('carModels.update');
Route::delete('/carModels/delete/{carModel}', 'CarModelsController@destroy')->name('carModels.destroy');
Route::post('/carModels/delete-selected', 'CarModelsController@deleteSelected')->name('carModels.deleteSelected');
