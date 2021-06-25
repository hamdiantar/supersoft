<?php

//Spare Parts
Route::get('/sub-parts-types', 'SubPartsTypesController@index')->name('sub.spare.parts.index');
Route::get('/sub-parts-types/create', 'SubPartsTypesController@create')->name('sub.spare.parts.create');
Route::get('/sub-parts-types/edit/{spare_part}', 'SubPartsTypesController@edit')->name('sub.spare.parts.edit');
Route::post('/sub-spare-parts/delete-selected', 'SubPartsTypesController@deleteSelected')->name('sub-spare-parts.deleteSelected');

Route::post('/sub-spare-parts/store', 'SubPartsTypesController@store')->name('sub-spare-parts.store');
Route::post('/sub-spare-parts/update/{sparePart}', 'SubPartsTypesController@update')->name('sub-spare-parts.update');
