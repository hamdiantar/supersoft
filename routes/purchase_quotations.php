<?php

Route::resource('/purchase-quotations', 'PurchaseQuotationsController');
Route::post('/purchase-quotations/select-part', 'PurchaseQuotationsController@selectPartRaw')->name('purchase.quotations.select.part');
Route::get('/purchase-quotations/print/data', 'PurchaseQuotationsController@print')->name('purchase.quotations.print');
Route::post('/purchase-quotations/terms', 'PurchaseQuotationsController@terms')->name('purchase.quotations.terms');

// PRICE SEGMENTS
Route::post('purchase-quotations/price-segments', 'PurchaseQuotationsController@priceSegments')->name('purchase.quotations.price.segments');

//ajax purchase quotations
Route::post('/purchase-quotations/select-purchase-request', 'PurchaseQuotationsController@selectPurchaseRequest')->name('purchase.quotations.select.request');

// purchase quotations execution
Route::post('/purchase-quotations-execution/save', 'PurchaseQuotationExecutionController@save')->name('purchase.quotations.execution.save');

// purchase quotations library
Route::post('purchase-quotations/library/get-files', 'PurchaseQuotationLibraryController@getFiles')->name('purchase.quotations.library.get.files');
Route::post('purchase-quotations/upload_library', 'PurchaseQuotationLibraryController@uploadLibrary')->name('purchase.quotations.upload_library');
Route::post('purchase-quotations/library/file-delete', 'PurchaseQuotationLibraryController@destroyFile')->name('purchase.quotations.library.file.delete');

