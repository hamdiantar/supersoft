<?php

//sales-invoices

Route::get('/sales-invoices', 'SalesInvoicesController@index')->name('sales.invoices.index');
Route::get('/sales-invoices/create', 'SalesInvoicesController@create')->name('sales.invoices.create');
Route::post('/sales-invoices/store', 'SalesInvoicesController@store')->name('sales.invoices.store');

//front end routes
Route::post('/sales-invoices-add-customer', 'SalesInvoicesFrontEndController@addCustomer')->name('sales.invoices.add.customer');
Route::post('/sales-invoices-customer-balance', 'SalesInvoicesFrontEndController@customerBalance')->name('sales.invoice.customer.balance');

// data by branch
Route::post('/sales-invoices-data-by-branch', 'SalesInvoicesFrontEndController@dataByBranch')->name('sales.invoices.data.by.branches');

//invoice items
Route::post('/sales-invoices-parts-details', 'SalesInvoicesFrontEndController@partDetails')->name('sales.invoices.parts.details');
Route::post('/sales.invoices.get.purchase.invoice.data','SalesInvoicesFrontEndController@purchaseInvoiceData')
    ->name('sales.invoices.get.purchase.invoice.data');

// store invoice
//Route::post('/sales-invoices-store', 'SalesInvoicesController@store')->name('sales.invoices.store');

// invoice RevenueReceipts
Route::get('/sales-invoices-revenue-receipts/{invoice}', 'SalesInvoicesController@revenueReceipts')
    ->name('sales.invoices.revenue.receipts');

// Edit sales-invoice
Route::get('/sales-invoices/edit/{invoice}', 'SalesInvoicesController@edit')->name('sales.invoices.edit');
Route::post('/sales-invoices/update/{invoice}', 'SalesInvoicesController@update')->name('sales.invoices.update');

// delete Invoice
Route::delete('/sales-invoices/delete/{invoice}', 'SalesInvoicesController@destroy')->name('sales.invoices.destroy');
Route::post('sales-invoices-deleteSelected', 'SalesInvoicesController@deleteSelected')->name('sales.invoices.deleteSelected');

// show Invoice
Route::get('/sales-invoices/show', 'SalesInvoicesController@show')->name('sales.invoices.show');

// points discount (get points rules )
Route::post('/customer/points/rules', 'SalesInvoicesFrontEndController@customerPointsRules')->name('customer.points.rules');
