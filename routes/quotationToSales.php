<?php
Route::post('quotations-to-sales-invoice', 'QuotationToSalesInvoiceController@quotationToSalesInvoice')->name('quotations.to.sales');

// to work card
Route::post('quotations-to-work-card', 'QuotationToWorkCardController@quotationToWorkCard')->name('quotations.to.work.card');

Route::post('quotations-customer-cars', 'QuotationToWorkCardController@customerCars')->name('quotations.customer.cars');
