<?php

Route::get('sales-invoices-return','SalesInvoiceReturnController@index')->name('sales.invoices.return.index');
Route::get('sales-invoices-return/create','SalesInvoiceReturnController@create')->name('sales.invoices.return.create');

//sales-invoice-data
Route::post('sales-invoices-data','SalesInvoiceReturnController@salesInvoiceData')->name('sales.invoices.data');

//store invoice
Route::post('sales-invoices-return/store','SalesInvoiceReturnController@store')->name('sales.invoices.return.store');

//expenses-receipts
Route::get('sales-invoices-return/expenses-receipts/{invoice}','SalesInvoiceReturnController@expensesReceipts')
    ->name('sales.invoices.return.expense.receipts');

// show invoice
Route::get('sales-invoices-return/show','SalesInvoiceReturnController@show')->name('sales.invoices.return.show');

//edit invoice
Route::get('sales-invoices-return/edit/{invoice}','SalesInvoiceReturnController@edit')->name('sales.invoices.return.edit');
Route::post('sales-invoices-return/update/{invoice}','SalesInvoiceReturnController@update')->name('sales.invoices.return.update');

// deleted invoice
Route::delete('sales-invoices-return/delete/{invoice}','SalesInvoiceReturnController@destroy')->name('sales.invoices.return.destroy');
Route::post('sales-invoices-return-deleteSelected', 'SalesInvoiceReturnController@deleteSelected')
    ->name('sales.invoices.return.deleteSelected');
