<?php

Route::get('/maintenance-status/report', 'MaintenanceStatusController@index')->name('maintenance.status.index.report');
Route::get('/maintenance-status/pending', 'MaintenanceStatusController@getMorePendingCards')->name('maintenance.status.pending');
Route::get('/maintenance-status/processing', 'MaintenanceStatusController@getMoreProcessingCards')->name('maintenance.status.processing');
Route::get('/maintenance-status/finished', 'MaintenanceStatusController@getMoreFinishedCards')->name('maintenance.status.finished');
Route::get('/maintenance-status/scheduled', 'MaintenanceStatusController@getMoreScheduledCards')->name('maintenance.status.scheduled');
