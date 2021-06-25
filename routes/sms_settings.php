<?php

//Settings
Route::get('/sms/settings', 'SmsSettingsController@edit')->name('sms.settings.edit');
Route::post('/sms/settings/update', 'SmsSettingsController@update')->name('sms.settings.update');
