<?php

//SETTLEMENTS
Route::resource('settlements', 'SettlementController');

//ajax
Route::post('settlements/select-part', 'SettlementController@selectPartRaw')->name('settlements.select.part');
Route::post('settlements/price-segments', 'SettlementController@priceSegments')->name('settlements.price.segments');
Route::post('settlements/deleteSelected', 'SettlementController@deleteSelected')->name('settlements.deleteSelected');
