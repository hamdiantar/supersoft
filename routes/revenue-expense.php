<?php
//revenues types
Route::get('/revenues_types', 'RevenueTypesController@index')->name('revenues_types.index');
Route::get('/revenues_types/create', 'RevenueTypesController@create')->name('revenues_types.create');
Route::post('/revenues_types/store', 'RevenueTypesController@store')->name('revenues_types.store');
Route::get('/revenues_types/edit/{revenueType}', 'RevenueTypesController@edit')->name('revenues_types.edit');
Route::put('/revenues_types/{revenueType}', 'RevenueTypesController@update')->name('revenues_types.update');
Route::delete('/revenues_types/delete/{revenueType}', 'RevenueTypesController@destroy')->name('revenues_types.destroy');
Route::post('/revenues_types/delete-selected', 'RevenueTypesController@deleteSelected')->name('revenues_types.deleteSelected');


//revenues Items
Route::get('/revenues_Items', 'RevenueItemsController@index')->name('revenues_Items.index');
Route::get('/revenues_Items/create', 'RevenueItemsController@create')->name('revenues_Items.create');
Route::post('/revenues_Items/store', 'RevenueItemsController@store')->name('revenues_Items.store');
Route::get('/revenues_Items/edit/{revenueItem}', 'RevenueItemsController@edit')->name('revenues_Items.edit');
Route::put('/revenues_Items/{revenueItem}', 'RevenueItemsController@update')->name('revenues_Items.update');
Route::delete('/revenues_Items/delete/{revenueItem}', 'RevenueItemsController@destroy')->name('revenues_Items.destroy');
Route::post('/revenues_Items/delete-selected', 'RevenueItemsController@deleteSelected')->name('revenues_Items.deleteSelected');
Route::get('/getRevenuesTypesByBranchID', 'RevenueItemsController@getRevenuesTypesByBranchID')->name('getRevenuesTypesByBranchID');

//revenues Receipts
Route::get('/revenueReceipts', 'RevenueReceiptsController@index')->name('revenueReceipts.index');
Route::get('/revenueReceipts/create', 'RevenueReceiptsController@create')->name('revenueReceipts.create');
Route::post('/revenueReceipts/store', 'RevenueReceiptsController@store')->name('revenueReceipts.store');
Route::get('/revenueReceipts/edit/{revenueReceipt}', 'RevenueReceiptsController@edit')->name('revenueReceipts.edit');
Route::put('/revenueReceipts/{revenueReceipt}', 'RevenueReceiptsController@update')->name('revenueReceipts.update');
Route::delete('/revenueReceipts/delete/{revenueReceipt}', 'RevenueReceiptsController@destroy')->name('revenueReceipts.destroy');
Route::post('/revenueReceipts/delete-selected', 'RevenueReceiptsController@deleteSelected')->name('revenueReceipts.deleteSelected');
Route::get('/revenueReceipts/getItems', 'RevenueReceiptsController@getRevenueItemsByTypeId')->name('revenueTypes.items');
Route::get('/revenueReceipts/getRevenueNumbersByBranch', 'RevenueReceiptsController@getRevenueNumbersByBranch')->name('revenueTypes.revenuesNumbers');
Route::get('/revenueReceipts/getRevenueReceiversByBranch', 'RevenueReceiptsController@getRevenueReceiversByBranch')->name('revenueTypes.receivers');


//Expenses types
Route::get('/expenses_types', 'ExpensesTypesController@index')->name('expenses_types.index');
Route::get('/expenses_types/create', 'ExpensesTypesController@create')->name('expenses_types.create');
Route::post('/expenses_types/store', 'ExpensesTypesController@store')->name('expenses_types.store');
Route::get('/expenses_types/edit/{expensesType}', 'ExpensesTypesController@edit')->name('expenses_types.edit');
Route::put('/expenses_types/{expensesType}', 'ExpensesTypesController@update')->name('expenses_types.update');
Route::delete('/expenses_types/delete/{expensesType}', 'ExpensesTypesController@destroy')->name('expenses_types.destroy');
Route::post('/expenses_types/delete-selected', 'ExpensesTypesController@deleteSelected')->name('expenses_types.deleteSelected');

//Expenses Items
Route::get('/expenses_items', 'ExpensesItemsController@index')->name('expenses_items.index');
Route::get('/expenses_items/create', 'ExpensesItemsController@create')->name('expenses_items.create');
Route::post('/expenses_items/store', 'ExpensesItemsController@store')->name('expenses_items.store');
Route::get('/expenses_items/edit/{expensesItem}', 'ExpensesItemsController@edit')->name('expenses_items.edit');
Route::put('/expenses_items/{expensesItem}', 'ExpensesItemsController@update')->name('expenses_items.update');
Route::delete('/expenses_items/delete/{expensesItem}', 'ExpensesItemsController@destroy')->name('expenses_items.destroy');
Route::post('/expenses_items/delete-selected', 'ExpensesItemsController@deleteSelected')->name('expenses_items.deleteSelected');
Route::get('/getExpensesTypesByBranchID', 'ExpensesItemsController@getExpensesTypesByBranchID')->name('getExpensesTypesByBranchID');

//Expenses Receipts
Route::get('/expenseReceipts', 'ExpensesReceiptsController@index')->name('expenseReceipts.index');
Route::get('/expenseReceipts/create', 'ExpensesReceiptsController@create')->name('expenseReceipts.create');
Route::post('/expenseReceipts/store', 'ExpensesReceiptsController@store')->name('expenseReceipts.store');
Route::get('/expenseReceipts/edit/{expenseReceipt}', 'ExpensesReceiptsController@edit')->name('expenseReceipts.edit');
Route::put('/expenseReceipts/{expenseReceipt}', 'ExpensesReceiptsController@update')->name('expenseReceipts.update');
Route::delete('/expenseReceipts/delete/{expenseReceipt}', 'ExpensesReceiptsController@destroy')->name('expenseReceipts.destroy');
Route::post('/expenseReceipts/delete-selected', 'ExpensesReceiptsController@deleteSelected')->name('expenseReceipts.deleteSelected');
Route::get('/expenseReceipts/getItems', 'ExpensesReceiptsController@getExpenseItemsByTypeId')->name('expenseTypes.items');
Route::get('/expenseReceipts/checkBalance', 'ExpensesReceiptsController@checkIFBalanceEnough')->name('expenseReceipts.checkBalance');
Route::get('/expenseReceipts/show/{expenseReceipt}', 'ExpensesReceiptsController@show')->name('expenseReceipts.show');
Route::get('/expenseReceipts/getExpenseNumbersByBranch', 'ExpensesReceiptsController@getExpenseNumbersByBranch')->name('expenseTypes.revenuesNumbers');
Route::get('/expenseReceipts/getExpenseReceiversByBranch', 'ExpensesReceiptsController@getExpenseReceiversByBranch')->name('expenseTypes.receivers');
