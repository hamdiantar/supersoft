<?php

Route::get('/notifications/{notification}/go-to', 'NotificationsController@goToLink')->name('notifications.go.to.link');

Route::post('/notifications/get_real', 'NotificationsController@getRealTimeNotification')->name('notifications.get.real');
