<?php

Route::get('work-cards/{work_card}/invoices-sample/create', 'CardInvoiceSampleController@create')
    ->name('work.cards.invoice.sample.create');

// STORE CARD INVOICES
Route::post('work-cards/{work_card}/invoices-sample/store', 'CardInvoiceSampleController@store')
    ->name('work.cards.invoice.sample.store');

Route::post('work-cards/{work_card}/invoices-sample/{cardInvoice}/update','CardInvoiceSampleController@update')
    ->name('work.cards.invoices.sample.update');


//Services
Route::post('card-invoices-sample-services-by-type', 'CardInvoiceFrontEndSampleController@getServicesByType')
    ->name('card.invoices.sample.services.by.type');

Route::post('card-invoices-sample-services-by-type-footer', 'CardInvoiceFrontEndSampleController@getServicesByTypeInFooter')
    ->name('card.invoices.sample.services.by.type.footer');

Route::post('card-invoices-sample-services-details', 'CardInvoiceFrontEndSampleController@getServicesDetails')
    ->name('card.invoices.sample.services.details');


//Packages
Route::post('card-invoices-sample-package-details', 'CardInvoiceFrontEndSampleController@packageDetails')
    ->name('card.invoices.sample.package.details');

//PARTS
Route::post('/card-invoices-sample-parts-details', 'CardInvoiceFrontEndSampleController@partDetails')->name('card.invoices.sample.parts.details');

Route::get('/card-invoices-sample-parts-by-type', 'CardInvoiceFrontEndSampleController@getPartsByType')
    ->name('card.invoices.sample.parts.by.type');

Route::post('/card-invoices-sample-purchase-invoice-data','CardInvoiceFrontEndSampleController@purchaseInvoiceData')
    ->name('card.invoices.sample.purchase.invoice.data');

Route::post('card-invoices-sample-parts-by-type-footer', 'CardInvoiceFrontEndSampleController@getPartsByTypeInFooter')
    ->name('card.invoices.sample.parts.by.type.footer');

// WINCH
Route::post('card-invoices-sample-winch-distance', 'CardInvoiceSampleController@getDistance')
    ->name('card.invoices.sample.winch.distance');

