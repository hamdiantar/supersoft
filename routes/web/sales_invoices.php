<?php

//sales-invoices
Route::get('/sales-invoices', 'SalesInvoiceController@index')->name('sales.invoices.index');

Route::get('/sales-invoices/show', 'SalesInvoiceController@show')->name('sales.invoices.show');
