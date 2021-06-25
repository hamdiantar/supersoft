<?php

// SUPPLIERS GROUPS
Route::resource('suppliers-groups', 'SuppliersGroupsController');
Route::post('suppliers-groups-deleteSelected', 'SuppliersGroupsController@deleteSelected')->name('suppliers.groups.deleteSelected');

// SUPPLIERS
Route::get('suppliers/get-select' ,'SuppliersController@getSuppliers')->name('get-suppliers-select');
Route::resource('suppliers', 'SuppliersController');
Route::post('suppliers-deleteSelected', 'SuppliersController@deleteSelected')->name('suppliers.deleteSelected');
Route::post('suppliers-groups-by-branch', 'SuppliersController@getSupplierGroupsByBranch')->name('suppliers.groups.by.branch.by.branch');

Route::post('suppliers/upload-upload_library', 'SuppliersController@uploadLibrary')->name('suppliers.upload.upload_library');
Route::post('suppliers/upload_library', 'SuppliersController@getFiles')->name('suppliers.upload_library');
Route::post('suppliers/upload_library/file-delete', 'SuppliersController@destroyFile')->name('suppliers.upload_library.file.delete');

Route::post('suppliers/get-sub-groups-by-main-ids', 'SuppliersController@getSubGroupsByMainIds')->name('suppliers.getSubGroupsByMainIds');
