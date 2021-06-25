<?php

//Settings
Route::get('/points/settings', 'PointsSettingsController@edit')->name('points.settings.edit');
Route::post('/points/settings/update', 'PointsSettingsController@update')->name('points.settings.update');
