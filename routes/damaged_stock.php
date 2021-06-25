<?php

//DAMAGED STOCK
Route::resource('damaged-stock', 'DamagedStockController');

//ajax
Route::post('damaged-stock/select-part', 'DamagedStockController@selectPartRaw')->name('damage.stock.select.part');
Route::post('damaged-stock/price-segments', 'DamagedStockController@priceSegments')->name('damage.stock.price.segments');


// Ajax employees
Route::post('damaged-stock/employees-percent', 'DamagedStockController@newEmployeesPercent')->name('damage.stock.employees.percent');
Route::post('damaged-stock/delete-employees', 'DamagedStockController@deleteEmployee')->name('damage.stock.delete.employee');
Route::post('damaged-stock/deleteSelected', 'DamagedStockController@deleteSelected')->name('damage.stock.deleteSelected');
