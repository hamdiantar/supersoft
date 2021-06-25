<?php
Route::get('/companies', 'CompaniesController@index')->name('companies.index');
Route::get('/companies/create', 'CompaniesController@create')->name('companies.create');
Route::post('/companies/store', 'CompaniesController@store')->name('companies.store');
Route::get('/companies/edit/{company}', 'CompaniesController@edit')->name('companies.edit');
Route::put('/companies/{company}', 'CompaniesController@update')->name('companies.update');
Route::delete('/companies/delete/{company}', 'CompaniesController@destroy')->name('companies.destroy');
Route::post('/companies/delete-selected', 'CompaniesController@deleteSelected')->name('companies.deleteSelected');
Route::get('/companies/car_models', 'CompaniesController@getModelsByCompany')->name('companies.models');
