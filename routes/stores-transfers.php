<?php
//stores transfers
Route::resource('stores-transfers', 'StoreTransferCont');

Route::post('stores-transfers-deleteSelected', 'StoreTransferCont@deleteSelected')->name('stores-transfers.deleteSelected');
Route::post('stores-transfers-get-store-parts' ,'StoreTransferCont@getStoreParts')->name('get-store-parts');
Route::get('stores-transfers-get-stores/{branch}' ,'StoreTransferCont@get_branch_stores')->name('get-branch-stores');

// new routes
Route::post('stores-transfers-select-part' ,'StoreTransferCont@selectPartRaw')->name('stores.transfers.select.part');
Route::post('stores-transfers-get-price-segments' ,'StoreTransferCont@getPriceSegments')->name('stores.transfers.get.price.segments');
