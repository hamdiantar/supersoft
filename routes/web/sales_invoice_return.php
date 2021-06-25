<?php

Route::get('sales-invoices-return','SalesInvoiceReturnController@index')->name('sales.invoices.return.index');

// show invoice
Route::get('sales-invoices-return/show','SalesInvoiceReturnController@show')->name('sales.invoices.return.show');
