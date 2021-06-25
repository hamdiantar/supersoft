<?php

//Settings
Route::get('/notification/settings', 'NotificationSettingsController@edit')->name('notification.settings.edit');
Route::post('/notification/settings/update', 'NotificationSettingsController@update')->name('notification.settings.update');
