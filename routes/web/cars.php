<?php
Route::post('cars/store','CarsController@store')->name('cars.store');
Route::delete('cars/{car}/destroy','CarsController@destroy')->name('cars.destroy');
Route::post('cars/edit','CarsController@edit')->name('cars.edit');
Route::post('cars/{car}/update','CarsController@update')->name('cars.update');
Route::post('cars/car-model','CarsController@getCarModel')->name('cars.models');
