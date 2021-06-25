<?php

Route::resource('work-cards', 'WorkCardsController');

// delete selected
Route::post('work-cards-deleteSelected', 'WorkCardsController@deleteSelected')->name('work.cards.deleteSelected');

// GET CUSTOMERS BY BRANCH
Route::post('work-cards-get-customers-by-branch', 'WorkCardsController@getCustomersByBranch')
    ->name('work.cards.customers.by.branch');

// GET CUSTOMER CARS
Route::post('work-cards-get-customer-cars', 'WorkCardsController@getCustomersCars')
    ->name('work.cards.get.customer.cars');

// GET CUSTOMER CARS
Route::post('work-cards-select-customer-car', 'WorkCardsController@selectCustomersCar')
    ->name('work.cards.select.customer.car');

// CREATE CARD INVOICES
Route::get('work-cards/{work_card}/invoices/create', 'CardInvoicesController@create')
    ->name('work.cards.invoice.create');

Route::post('work-cards/invoices/validation', 'CardInvoicesController@validation')
    ->name('work-cards-invoices.validation');

Route::post('work-cards/invoices/store', 'CardInvoicesController@store')
    ->name('work-cards-invoices.store');

// EDIT CARD INVOICES
Route::get('work-cards/{work_card}/invoices/{card_invoice}/edit', 'CardInvoicesController@edit')
    ->name('work.cards.invoices.edit');

Route::post('work-cards/invoices/update-validation', 'CardInvoicesController@updateValidation')
    ->name('work-cards-invoices.update.validation');

Route::post('work-cards/{work_card}/invoices/{card_invoice}/update', 'CardInvoicesController@update')
    ->name('work-cards-invoices.update');

Route::post('work-cards/invoices/show-images', 'CardInvoicesController@showImages')
    ->name('work-cards-invoices.show.images');


// invoice RevenueReceipts
Route::get('/card-invoices-revenue-receipts/{card_invoice}', 'CardInvoicesController@revenueReceipts')
    ->name('card.invoices.revenue.receipts');


//PARTS
Route::post('/card-invoices-parts-details', 'CardInvoicesFrontEndController@partDetails')->name('card.invoices.parts.details');
Route::get('/card-invoices-parts-by-type', 'CardInvoicesFrontEndController@getPartsByType')
    ->name('card.invoices.parts.by.type');

Route::post('/card-invoices-purchase-invoice-data','CardInvoicesFrontEndController@purchaseInvoiceData')
    ->name('card.invoices.purchase.invoice.data');

Route::post('card-invoices-parts-by-type-footer', 'CardInvoicesFrontEndController@getPartsByTypeInFooter')
    ->name('card.invoices.parts.by.type.footer');

//Services
Route::post('card-invoices-services-by-type', 'CardInvoicesFrontEndController@getServicesByType')
    ->name('card.invoices.services.by.type');

Route::post('card-invoices-services-by-type-footer', 'CardInvoicesFrontEndController@getServicesByTypeInFooter')
    ->name('card.invoices.services.by.type.footer');

Route::post('card-invoices-services-details', 'CardInvoicesFrontEndController@getServicesDetails')
    ->name('card.invoices.services.details');

//Packages
Route::post('card-invoices-package-details', 'CardInvoicesFrontEndController@packageDetails')
    ->name('card.invoices.package.details');
//Route::post('quotations-package-info', 'QuotationsController@packageInfo')->name('quotations.package.info');

//print
Route::get('/card-invoices-show', 'WorkCardsController@show')->name('card.invoices.show');

//follow up
Route::post('/add-follow-up', 'WorkCardsController@addFollowUp')->name('add.follow.up');

// WINCH
Route::post('card-invoices-winch-distance', 'CardInvoicesController@getDistance')
    ->name('card.invoices.winch.distance');

