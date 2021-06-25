<?php
//purchase-invoices-returns
Route::get('/purchase-returns', 'PurchaseReturnsController@index')->name('purchase_returns.index');
Route::get('/purchase-returns/create', 'PurchaseReturnsController@create')->name('purchase_returns.create');
Route::get('/purchase-returns/edit/{purchaseReturn}', 'PurchaseReturnsController@edit')->name('purchase_returns.edit');
Route::post('/purchase-returns/store', 'PurchaseReturnsController@store')->name('purchase_returns.store');
Route::put('/purchase-returns/update/{purchaseReturn}', 'PurchaseReturnsController@update')->name('purchase_returns.update');
Route::delete('/purchase-returns/destroy/{purchaseReturn}', 'PurchaseReturnsController@destroy')->name('purchase_returns.destroy');
Route::get('/purchase-returns/revenues/{id}', 'PurchaseReturnsController@showRevenues')->name('purchase_returns.revenues');
Route::post('/purchase-returns/get-Purchase-invoice-by-id', 'PurchaseReturnsController@getPurchaseInvoice')->name('purchase_returns.getPurchaseInvoice');
Route::get('/purchase-returns/show', 'PurchaseReturnsController@show')->name('purchase_returns.show');
Route::get('/purchase-returns/getInvoiceByBranch', 'PurchaseReturnsController@getInvoiceByBranch')->name('purchase_returns.getInvoiceByBranch');
Route::post('/purchase-returns/delete-selected', 'PurchaseReturnsController@deleteSelected')->name('purchase_returns.deleteSelected');
