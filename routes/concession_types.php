<?php

\Route::resource('/concession-types', 'ConcessionTypesController');
\Route::post('/concession-types/deleteSelected', 'ConcessionTypesController@deleteSelected')->name('concession-types.deleteSelected');
\Route::post('/concession-items/by-type', 'ConcessionTypesController@getConcessionItems')->name('concession-items.by.type');


//concession relation
\Route::get('/concession-relation', 'ConcessionRelationController@index')->name('concession-relations.index');
\Route::get('/concession-relation/create', 'ConcessionRelationController@createRelation')->name('concession-relations.create');
\Route::post('/concession-relation/store', 'ConcessionRelationController@store')->name('concession-relations.store');

\Route::get('/concession-relation/{type}/edit', 'ConcessionRelationController@edit')->name('concession-relations.edit');
\Route::post('/concession-relation/update/{type}', 'ConcessionRelationController@update')->name('concession-relations.update');

\Route::post('/concession-relation/delete/{type}', 'ConcessionRelationController@destroy')->name('concession-relations.destroy');
