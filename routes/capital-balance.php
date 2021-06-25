<?php
//customers categories
Route::resource('capital-balance', 'CapitalBalanceController')->only(['index', 'show', 'create' ,'store' ,'edit' ,'update']);
Route::post('capital-balance-deleteSelected', 'CapitalBalanceController@deleteSelected')->name('capital-balance.deleteSelected');
