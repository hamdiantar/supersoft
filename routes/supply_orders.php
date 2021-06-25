<?php

Route::resource('/supply-orders', 'SupplyOrderController');

//purchase quotations
Route::post('/supply-orders/purchase-quotations', 'SupplyOrderController@getPurchaseQuotations')->name('supply.orders.purchase-quotations');
Route::post('/supply-orders/add-purchase-quotations', 'SupplyOrderController@addPurchaseQuotations')->name('supply.orders.add.purchase-quotations');

Route::post('/supply-orders/select-part', 'SupplyOrderController@selectPartRaw')->name('supply.orders.select.part');
Route::get('/supply-orders/print/data', 'SupplyOrderController@print')->name('supply.orders.print');
Route::post('/supply-orders/terms', 'SupplyOrderController@terms')->name('supply.orders.terms');

// PRICE SEGMENTS
Route::post('supply-orders/price-segments', 'SupplyOrderController@priceSegments')->name('supply.orders.price.segments');

// purchase quotations execution
Route::post('/supply-orders-execution/save', 'SupplyOrderExecutionController@save')->name('supply.orders.execution.save');

// purchase quotations library
Route::post('supply-orders/library/get-files', 'SupplyOrderLibraryController@getFiles')->name('supply.orders.library.get.files');
Route::post('supply-orders/upload_library', 'SupplyOrderLibraryController@uploadLibrary')->name('supply.orders.upload_library');
Route::post('supply-orders/library/file-delete', 'SupplyOrderLibraryController@destroyFile')->name('supply.orders.library.file.delete');
