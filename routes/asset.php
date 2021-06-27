<?php
//assets Type
Route::get('/assets-type', 'AssetsTypeController@index')->name('assetsType.index');
Route::get('/assets-type/create', 'AssetsTypeController@create')->name('assetsType.create');
Route::post('/assets-type/store', 'AssetsTypeController@store')->name('assetsType.store');
Route::get('/assets-type/edit/{assetType}', 'AssetsTypeController@edit')->name('assetsType.edit');
Route::put('/assets-type/{assetType}', 'AssetsTypeController@update')->name('assetsType.update');
Route::delete('/assets-type/delete/{assetType}', 'AssetsTypeController@destroy')->name('assetsType.destroy');
Route::post('/assets-type/delete-selected', 'AssetsTypeController@deleteSelected')->name('assetsType.deleteSelected');


//assets Group
Route::get('/assets-groups', 'AssetsGroupController@index')->name('assetsGroup.index');
Route::get('/assets-groups/create', 'AssetsGroupController@create')->name('assetsGroup.create');
Route::post('/assets-groups/store', 'AssetsGroupController@store')->name('assetsGroup.store');
Route::get('/assets-groups/edit/{assetGroup}', 'AssetsGroupController@edit')->name('assetsGroup.edit');
Route::put('/assets-groups/{assetGroup}', 'AssetsGroupController@update')->name('assetsGroup.update');
Route::delete('/assets-groups/delete/{assetGroup}', 'AssetsGroupController@destroy')->name('assetsGroup.destroy');
Route::post('/assets-groups/delete-selected', 'AssetsGroupController@deleteSelected')->name('assetsGroup.deleteSelected');

//assets
Route::get('/assets', 'AssetsController@index')->name('assets.index');
Route::get('/assets/show', 'AssetsController@show')->name('assets.show');
Route::get('/assets/create', 'AssetsController@create')->name('assets.create');
Route::post('/assets/store', 'AssetsController@store')->name('assets.store');
Route::get('/assets/edit/{asset}', 'AssetsController@edit')->name('assets.edit');
Route::put('/assets/{asset}', 'AssetsController@update')->name('assets.update');
Route::delete('/assets/delete/{asset}', 'AssetsController@destroy')->name('assets.destroy');
Route::post('/assets/delete-selected', 'AssetsController@deleteSelected')->name('assets.deleteSelected');


Route::post('assets/AssetsGroupsByBranchId', 'AssetsController@getAssetsGroupsByBranchId')->name('assets.getAssetsGroupsByBranchId');
Route::post('assets/AssetsTypesByBranchId', 'AssetsController@getAssetsTypesByBranchId')->name('assets.getAssetsTypesByBranchId');
Route::post('assets/AssetsGroupsAnnualConsumtionRate', 'AssetsController@getAssetsGroupsAnnualConsumtionRate')->name('assets.getAssetsGroupsAnnualConsumtionRate');
//assets Employees
Route::get('/assets-employees/{asset}', 'AssetsEmployeesController@index')->name('assetsEmployees.index');
Route::post('/assets-employees/store', 'AssetsEmployeesController@store')->name('assetsEmployees.store');
Route::delete('/assets-employees/delete/{assetEmployee}', 'AssetsEmployeesController@destroy')->name('assetsEmployees.destroy');
Route::post('/assets-employees/delete-selected', 'AssetsEmployeesController@deleteSelected')->name('assetsEmployees.deleteSelected');

//assets Insurance
Route::get('/assets-insurances/{asset}', 'AssetsInsurancesController@index')->name('assetsInsurances.index');
Route::post('/assets-insurances/store', 'AssetsInsurancesController@store')->name('assetsInsurances.store');
Route::delete('/assets-insurances/delete/{assetInsurance}', 'AssetsInsurancesController@destroy')->name('assetsInsurances.destroy');
Route::post('/assets-insurances/delete-selected', 'AssetsInsurancesController@deleteSelected')->name('assetsInsurances.deleteSelected');

//assets licenses
Route::get('/assets-licenses/{asset}', 'AssetsLicensesController@index')->name('assetsLicenses.index');
Route::post('/assets-licenses/store', 'AssetsLicensesController@store')->name('assetsLicenses.store');
Route::delete('/assets-licenses/delete/{assetLicense}', 'AssetsLicensesController@destroy')->name('assetsLicenses.destroy');
Route::post('/assets-licenses/delete-selected', 'AssetsLicensesController@deleteSelected')->name('assetsLicenses.deleteSelected');


Route::get('/assets-examinations/{asset}', 'AssetsExaminationsController@index')->name('assetsExaminations.index');
Route::post('/assets-examinations/store', 'AssetsExaminationsController@store')->name('assetsExaminations.store');
Route::delete('/assets-examinations/delete/{assetExamination}', 'AssetsExaminationsController@destroy')->name('assetsExaminations.destroy');
Route::post('/assets-examinations/delete-selected', 'AssetsExaminationsController@deleteSelected')->name('assetsExaminations.deleteSelected');



Route::get('assets_expenses_items/assets_expenses_types_by_branch_id', 'AssetsTypeExpenseController@getExpensesTypesByBranch')->name('assets_expenses_types_by_branch_id');
Route::post('assets_expenses_types/delete-selected', 'AssetsTypeExpenseController@deleteSelected')->name('assets_expenses_types.deleteSelected');
Route::resource('assets_expenses_types', 'AssetsTypeExpenseController');
Route::post('assets_expenses_items/delete-selected', 'AssetsItemExpenseController@deleteSelected')->name('assets_expenses_items.deleteSelected');
Route::resource('assets_expenses_items', 'AssetsItemExpenseController');
Route::get('assets_expenses/getAssetsByAssetGroup', 'AssetExpenseController@getAssetsByAssetGroup')->name('assets_expenses.getAssetsByAssetGroup');
Route::get('assets_expenses/getItemsByAssetId', 'AssetExpenseController@getItemsByAssetId')->name('assets_expenses.getItemsByAssetId');
Route::resource('assets_expenses', 'AssetExpenseController');
Route::post('assets_expenses/deleteSelected', 'AssetExpenseController@deleteSelected')->name('assets_expenses.deleteSelected');
