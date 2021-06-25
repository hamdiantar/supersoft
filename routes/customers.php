<?php
//customers categories
Route::resource('customers-categories', 'CustomerCategoriesController');
Route::post('customers-categories-deleteSelected', 'CustomerCategoriesController@deleteSelected')
    ->name('customers-categories.deleteSelected');

Route::get('cars-print-barcode', 'CarsController@printBarcode')->name('cars.print.barcode');

//customers
Route::get('/customers', 'CustomerCarsController@index')->name('customers.index');
Route::get('/customers/get-select' ,'CustomerCarsController@getCustomers')->name('get-customers-select');
Route::get('/customers/create', 'CustomerCarsController@create')->name('customers.create');
Route::post('/customers/store', 'CustomerCarsController@store')->name('customers.store');
Route::get('/customers/edit/{customer}', 'CustomerCarsController@edit')->name('customers.edit');
Route::put('/customers/{customer}', 'CustomerCarsController@update')->name('customers.update');
Route::delete('/customers/delete/{customer}', 'CustomerCarsController@destroy')->name('customers.destroy');
Route::post('/customers/delete-selected', 'CustomerCarsController@deleteSelected')->name('customers.deleteSelected');
Route::get('/customers/add-car', 'CustomerCarsController@addCar')->name('customers.addCar');
Route::get('/getCustomerCategories', 'CustomerCarsController@getCustomerCategories')->name('customers.customerCategory');

Route::get('customer/{id}/cars', 'CarsController@index')->name('cars');
Route::get('customer/{id}/cars/indexModal', 'CarsController@indexModal')->name('cars.modal');
Route::post('customer/add-car', 'CarsController@AddCar')->name('addCar');
Route::put('customer/edit-car', 'CarsController@updateCar')->name('editCar');
Route::delete('customer/delete/car/{car}', 'CarsController@removeCar')->name('removeCar');

Route::get('customer/{customer}', 'CarsController@show')->name('customers.show');

Route::post('customers/upload-upload_library', 'CustomerLibraryController@uploadLibrary')->name('customers.upload.upload_library');
Route::post('customers/upload_library', 'CustomerLibraryController@getFiles')->name('customers.upload_library');
Route::post('customers/upload_library/file-delete', 'CustomerLibraryController@destroyFile')->name('customers.upload_library.file.delete');
