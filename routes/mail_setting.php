<?php

//Settings
Route::get('/mail/settings', 'MailSettingController@edit')->name('mail.settings.edit');
Route::post('/mail/settings/update', 'MailSettingController@update')->name('mail.settings.update');
