<?php

Route::resource('/purchase-requests', 'PurchaseRequestController');
Route::post('/purchase-requests/select-part', 'PurchaseRequestController@selectPart')->name('purchase.requests.select.part');
Route::get('/purchase-requests/print/data', 'PurchaseRequestController@print')->name('purchase.requests.print');
Route::patch('/purchase-requests/approval/{purchaseRequest}', 'PurchaseRequestController@approval')->name('purchase.requests.approval');


// purchase requests execution
Route::post('/purchase-requests-execution/save', 'PurchaseRequestExecutionController@save')->name('purchase.requests.execution.save');

// purchase request library
Route::post('purchase-requests/library/get-files', 'PurchaseRequestLibraryController@getFiles')->name('purchase.requests.library.get.files');
Route::post('purchase-requests/upload_library', 'PurchaseRequestLibraryController@uploadLibrary')->name('purchase.requests.upload_library');
Route::post('purchase-requests/library/file-delete', 'PurchaseRequestLibraryController@destroyFile')->name('purchase.requests.library.file.delete');
