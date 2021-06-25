
<?php
Route::get('quotations', 'QuotationsController@index')->name('quotations.index');
Route::get('quotations/create', 'QuotationsController@create')->name('quotations.create');
Route::get('quotations/edit/{quotation}', 'QuotationsController@edit')->name('quotations.edit');
Route::post('quotations/update/{quotation}', 'QuotationsController@update')->name('quotations.update');
Route::get('quotations/show', 'QuotationsController@show')->name('quotations.show');
Route::delete('quotations/destroy/{quotation}', 'QuotationsController@destroy')->name('quotations.destroy');
Route::post('quotations-deleteSelected', 'QuotationsController@deleteSelected')->name('quotations.deleteSelected');
//Route::delete('quotations/deleteSelected', 'QuotationsController@destroy')->name('quotations.destroy');
Route::post('quotations/store', 'QuotationsController@store')->name('quotations.store');

//parts
Route::post('quotations-get-parts', 'QuotationsController@getParts')->name('quotations.get.parts');
Route::post('quotations-get-services', 'QuotationsController@getServices')->name('quotations.get.services');
Route::post('quotations-get-packages', 'QuotationsController@getServicesPackage')->name('quotations.get.packages');
Route::post('quotations-parts-by-type-footer', 'QuotationsController@getPartsByTypeInFooter')->name('quotations.parts.by.type.footer');

//Invoice items
Route::post('/quotations-parts-details', 'QuotationsController@partDetails')->name('quotations.parts.details');
Route::post('/quotations-get-purchase-invoice-data','QuotationsController@purchaseInvoiceData')
    ->name('quotations.get.purchase.invoice.data');

//Services
Route::post('quotations-get-services-by-type', 'QuotationsController@getServicesByType')->name('quotations.get.services.by.type');
Route::post('quotations-services-by-type-footer', 'QuotationsController@getServicesByTypeInFooter')
    ->name('quotations.services.by.type.footer');

Route::post('quotations-get-services-details', 'QuotationsController@getServicesDetails')->name('quotations.get.services.details');

//Packages
Route::post('quotations-get-packages', 'QuotationsController@getPackages')->name('quotations.get.packages');
Route::post('quotations-package-details', 'QuotationsController@packageDetails')->name('quotations.package.details');
Route::post('quotations-package-info', 'QuotationsController@packageInfo')->name('quotations.package.info');

//WINCH
Route::post('quotations-winch-get-distance', 'QuotationsController@getDistance')->name('quotations.winch.get.distance');
