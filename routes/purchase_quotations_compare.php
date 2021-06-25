<?php

Route::get('/purchase-quotations-compare', 'PurchaseQuotationCompareController@index')->name('purchase.quotations.compare.index');
Route::post('/purchase-quotations-compare/store', 'PurchaseQuotationCompareController@store')->name('purchase.quotations.compare.store');
