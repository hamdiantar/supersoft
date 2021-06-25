<?php
Route::resource('/concessions', 'ConcessionController')->except('show');
Route::get('/concessions/show', 'ConcessionController@show')->name('concessions.show');
Route::post('/concessions/deleteSelected', 'ConcessionController@deleteSelected')->name('concessions.deleteSelected');

Route::post('/concessions/get-types', 'ConcessionServiceController@getConcessionTypes')->name('concessions.get.types');
Route::post('/concessions/get-types-index-search', 'ConcessionServiceController@getConcessionTypesIndexSearch')
    ->name('concessions.get.types.index.search');

Route::post('/concessions/get-data-by-branch', 'ConcessionServiceController@getDataByBranch')->name('concessions.data.by.branch');

Route::post('/concessions/get-items', 'ConcessionServiceController@getItems')->name('concessions.get.items');
Route::post('/concessions/get-items-index-search', 'ConcessionServiceController@getItemsIndexSearch')->name('concessions.get.items.index.search');

Route::post('/concessions/parts-data', 'ConcessionServiceController@getPartsData')->name('concessions.parts.data');
Route::put('concessions/restore-delete/{concession}', 'ConcessionController@restoreDelete')->name('concessions.restore_delete');
Route::get('concessions/archive', 'ConcessionController@archive')->name('concessions.archive');
Route::delete('concessions-archiveData/{concession}', 'ConcessionController@archiveData')->name('concessions.archiveData');


// concession execution
Route::post('/concessions-execution/save', 'ConcessionExecutionController@save')->name('concessions.execution.save');

