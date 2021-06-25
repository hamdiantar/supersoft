<?php
//Spare Parts
Route::get('/spare-parts', 'SparePartsController@index')->name('spare-parts.index');
Route::get('/spare-parts/create', 'SparePartsController@create')->name('spare-parts.create');
Route::post('/spare-parts/store', 'SparePartsController@store')->name('spare-parts.store');
Route::get('/spare-parts/edit/{sparePart}', 'SparePartsController@edit')->name('spare-parts.edit');
Route::put('/spare-parts/{sparePart}', 'SparePartsController@update')->name('spare-parts.update');
Route::delete('/spare-parts/delete/{sparePart}', 'SparePartsController@destroy')->name('spare-parts.destroy');
Route::post('/spare-parts/delete-selected', 'SparePartsController@deleteSelected')->name('spare-parts.deleteSelected');



//Route::get('/sub-parts-types', 'SubPartsTypesController@index')->name('sub.spare.parts.index');
//Route::get('/sub-parts-types/create', 'SubPartsTypesController@create')->name('sub.spare.parts.create');
//Route::get('/sub-parts-types/edit/{spare_part}', 'SubPartsTypesController@edit')->name('sub.spare.parts.edit');
//Route::post('/sub-spare-parts/delete-selected', 'SubPartsTypesController@deleteSelected')->name('sub-spare-parts.deleteSelected');




//Spare Parts Units
Route::get('/spare-part-units', 'SparePartUnitsController@index')->name('spare-part-units.index');
Route::get('/spare-part-units/create', 'SparePartUnitsController@create')->name('spare-part-units.create');
Route::post('/spare-part-units/store', 'SparePartUnitsController@store')->name('spare-part-units.store');
Route::get('/spare-part-units/edit/{sparePartUnit}', 'SparePartUnitsController@edit')->name('spare-part-units.edit');
Route::put('/spare-part-units/{sparePartUnit}', 'SparePartUnitsController@update')->name('spare-part-units.update');
Route::delete('/spare-part-units/delete/{sparePartUnit}', 'SparePartUnitsController@destroy')->name('spare-part-units.destroy');
Route::post('/spare-part-units/delete-selected', 'SparePartUnitsController@deleteSelected')->name('spare-part-units.deleteSelected');
Route::post('/spare-part-units/delete-selected', 'SparePartUnitsController@deleteSelected')->name('spare-part-units.deleteSelected');

//stores
Route::get('/stores', 'StoresController@index')->name('stores.index');
Route::get('/stores/create', 'StoresController@create')->name('stores.create');
Route::post('/stores/store', 'StoresController@store')->name('stores.store');
Route::get('/stores/edit/{store}', 'StoresController@edit')->name('stores.edit');
Route::put('/stores/{store}', 'StoresController@update')->name('stores.update');
Route::delete('/stores/delete/{store}', 'StoresController@destroy')->name('stores.destroy');
Route::post('/stores/delete-selected', 'StoresController@deleteSelected')->name('stores.deleteSelected');
Route::post('stores/new-employee', 'StoresController@newEmployee')->name('stores.new.employee');
Route::get('stores/getById-employee', 'StoresController@getEmployeeBYId')->name('stores.getBYId.employee');
Route::post('stores/getEmployeeByBranchId', 'StoresController@getEmployeeByBranchId')->name('stores.getEmployeeByBranchId');
Route::post('stores/show', 'StoresController@show')->name('stores.show');


//parts
Route::resource('parts', 'PartsController');
Route::post('part-deleteSelected', 'PartsController@deleteSelected')->name('parts.deleteSelected');
Route::get('part-print-barcode', 'PartsController@printBarcode')->name('parts.print.barcode');
Route::get('getPartsBySparePartID', 'PartsController@getPartsBySparePartID')->name('getPartsBySparePartID');
Route::get('part-type-by-branch', 'PartsController@partsTypeByBranch')->name('part.type.by.branch');

Route::post('parts-new-supplier', 'PartsController@newSupplier')->name('parts.new.supplier');
Route::get('parts-getById-supplier', 'PartsController@getSupplierBYId')->name('parts.getBYId.supplier');

// Part-price-segments
Route::post('/parts/new-price-segment', 'PartsController@newPartPriceSegment')->name('parts.new.price.segments');
Route::post('/parts/delete-price-segment', 'PartsController@deletePartPriceSegment')->name('parts.delete.price.segments');

//PARTS LIBRARY
Route::post('parts/upload-upload_library', 'PartLibraryController@uploadLibrary')->name('parts.upload.upload_library');
Route::post('parts/upload_library', 'PartLibraryController@getFiles')->name('parts.upload_library');
Route::post('parts/upload_library/file-delete', 'PartLibraryController@destroyFile')->name('parts.upload_library.file.delete');

//new units
Route::post('part-units/new', 'PartUnitsController@newUnit')->name('part.units.new');
Route::post('part-units/update', 'PartUnitsController@update')->name('part.units.update');
Route::post('part-units/destroy', 'PartUnitsController@destroy')->name('part.units.destroy');

// MAIN PARTS AND SUB PARTS
Route::post('sub-parts-types/get', 'PartsController@getSubPartsTypes')->name('sub.parts.get');


Route::get('part-types' ,'PartTypesController@index')->name('part-types');
Route::post('part-types/store-superadmin' ,'PartTypesController@storeForSuperAdmin')->name('part-types.superadmin-store');
Route::post('part-types/store-normaladmin' ,'PartTypesController@storeForNormalAdmin')->name('part-types.normaladmin-store');
Route::post('part-types/update-superadmin/{id}' ,'PartTypesController@updateForSuperAdmin')->name('part-types.superadmin-update');
Route::post('part-types/update-normaladmin/{id}' ,'PartTypesController@updateForNormalAdmin')->name('part-types.normaladmin-update');
Route::get('part-types/delete' ,'PartTypesController@deletePart')->name('part-types.delete');
Route::get('part-types/main-part-types' ,'PartTypesController@mainPartTypesSelect')->name('part-types.main-part-types');
Route::get('part-types/getAllParts' ,'PartTypesController@getAllParts')->name('part-types.getAllParts');
Route::get('part-types/sub-part-types' ,'PartTypesController@subPartTypesSelect')->name('part-types.sub-part-types');
Route::get('part-types/{action}' ,'PartTypesController@getModalForm')->name('part-types.form');
// other route will be here

// PART TAXES
Route::post('parts/taxes-save', 'PartsController@saveTaxes')->name('parts.taxes.save');



Route::get('suppliers-groups-tree' ,'SuppliersGroupsTreeController@index')->name('supplier-group-tree');
Route::post('supplier-group/store-superadmin' ,'SuppliersGroupsTreeController@storeForSuperAdmin')->name('supplier-group.superadmin-store');
Route::post('supplier-group/store-normaladmin' ,'SuppliersGroupsTreeController@storeForNormalAdmin')->name('supplier-group.normaladmin-store');
Route::post('supplier-group/update-superadmin/{id}' ,'SuppliersGroupsTreeController@updateForSuperAdmin')->name('supplier-group.superadmin-update');
Route::post('supplier-group/update-normaladmin/{id}' ,'SuppliersGroupsTreeController@updateForNormalAdmin')->name('supplier-group.normaladmin-update');
Route::get('supplier-group/delete' ,'SuppliersGroupsTreeController@deleteSupplierGroup')->name('supplier-group.delete');

Route::get('supplier-group/main-part-types' ,'SuppliersGroupsTreeController@mainPartTypesSelect')->name('supplier-group.main-part-types');
Route::get('supplier-group/sub-part-types' ,'SuppliersGroupsTreeController@subPartTypesSelect')->name('supplier-group.sub-part-types');
Route::get('supplier-group/{action}' ,'SuppliersGroupsTreeController@getModalForm')->name('supplier-group.form');
// other route will be here

