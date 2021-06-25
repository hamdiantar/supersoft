<?php
//purchase-invoices
Route::get('/purchase-invoices', 'PurchaseInvoicesController@index')->name('purchase-invoices.index');
Route::get('/purchase-invoices/create', 'PurchaseInvoicesController@create')->name('purchase-invoices.create');
Route::get('/purchase-invoices/edit/{purchase_invoice}', 'PurchaseInvoicesController@edit')->name('purchase-invoices.edit');

Route::get('/purchase-invoices/show', 'PurchaseInvoicesController@show')->name('purchase-invoices.show');
Route::delete('/purchase-invoices/delete/{purchase_invoice}', 'PurchaseInvoicesController@destroy')->name('purchase-invoices.destroy');
Route::post('/purchase-invoices/delete-selected', 'PurchaseInvoicesController@deleteSelected')->name('purchase-invoices.deleteSelected');

Route::put('/purchase-invoices/edit/{purchase_invoice}', 'PurchaseInvoicesController@update')->name('purchase-invoices.update');

Route::post('/purchase-invoices/store', 'PurchaseInvoicesController@store')->name('purchase-invoices.store');
Route::get('/purchase-invoices/expenses/{id}', 'PurchaseInvoicesController@showExpenseForInvoice')->name('purchase-invoices.expenses');
Route::get('/purchase-invoices/getPartsBySparePartId', 'PurchaseInvoicesController@getPartsBySparePartId')->name('purchase-invoices.parts');
Route::get('/purchase-invoices/getPartsDetailsByID', 'PurchaseInvoicesController@getPartsDetailsByID')->name('purchase-invoices.parts.details');


// suppliers routes
Route::post('/purchase-invoice/add-supplier', 'PurchaseInvoicesController@addSupplier')->name('purchase.invoice.add.supplier');
Route::post('/purchase-invoice/supplier-balance', 'PurchaseInvoicesController@supplierBalance')->name('purchase.invoice.supplier.balance');


Route::get('/purchase-invoices/getDataByBranch', 'PurchaseInvoicesController@getDataByBranch')->name('purchase-invoices.getDataByBranch');


// unit prices
Route::get('/purchase-invoices/part-unit-prices', 'PurchaseInvoicesController@unitPrices')->name('purchase.invoices.part.unit.prices');
