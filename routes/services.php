<?php

//services types
Route::get('service-types/archive', 'ServicesTypesController@serviceTypeArchive')->name('services.types.archive');
Route::delete('services-types/force-delete/{user}', 'ServicesTypesController@forceDelete')->name('services.types.force_delete');
Route::put('services-types/restore-delete/{user}', 'ServicesTypesController@restoreDelete')->name('services.types.restore_delete');
Route::post('services-types-archiveSelected', 'ServicesTypesController@archiveSelected')->name('services.types.archiveSelected');
Route::post('services-types-restoreSelected', 'ServicesTypesController@restoreSelected')->name('services.types.restoreSelected');
Route::post('services-types-deleteSelected', 'ServicesTypesController@deleteSelected')->name('services.types.deleteSelected');

Route::resource('services-types', 'ServicesTypesController');


//services
Route::get('services/archive', 'ServicesController@serviceArchive')->name('services.archive');
Route::delete('services/force-delete/{user}', 'ServicesController@forceDelete')->name('services.force_delete');
Route::put('services/restore-delete/{user}', 'ServicesController@restoreDelete')->name('services.restore_delete');
Route::post('services-archiveSelected', 'ServicesController@archiveSelected')->name('services.archiveSelected');
Route::post('services-restoreSelected', 'ServicesController@restoreSelected')->name('services.restoreSelected');
Route::resource('services', 'ServicesController');
Route::post('services-deleteSelected', 'ServicesController@deleteSelected')->name('services.deleteSelected');
Route::post('services-types-by-branch', 'ServicesController@getServicesTypesByBranch')->name('services.types.by.branch');

//maintenance detection
Route::resource('maintenance-detection-types', 'MaintenanceDetectionTypesController');
Route::resource('maintenance-detections', 'MaintenanceDetectionsController');
Route::post('maintenance-detections-types-by-branch', 'MaintenanceDetectionsController@getMaintenanceTypesByBranch')
    ->name('maintenance.detections.types.by.branch');

Route::post('maintenance-detections-deleteSelected', 'MaintenanceDetectionsController@deleteSelected')
    ->name('maintenance.detection.deleteSelected');
Route::post('maintenance-detection-types-deleteSelected', 'MaintenanceDetectionTypesController@deleteSelected')
    ->name('maintenance.detection.types.deleteSelected');

//services packages
Route::get('/services_packages', 'ServicesPackagesController@index')->name('services_packages.index');
Route::get('/services_packages/create', 'ServicesPackagesController@create')->name('services_packages.create');
Route::post('/services_packages/store', 'ServicesPackagesController@store')->name('services_packages.store');
Route::get('/services_packages/edit/{servicePackage}', 'ServicesPackagesController@edit')->name('services_packages.edit');
Route::put('/services_packages/{servicePackage}', 'ServicesPackagesController@update')->name('services_packages.update');
Route::delete('/services_packages/delete/{servicePackage}', 'ServicesPackagesController@destroy')->name('services_packages.destroy');
Route::post('/services_packages/delete-selected', 'ServicesPackagesController@deleteSelected')->name('services_packages.deleteSelected');
Route::get('/services_packages/services', 'ServicesPackagesController@getServicesByServiceTypeId')->name('services_packages.services');
Route::get('/services_packages/services/details', 'ServicesPackagesController@getServiceDetails')->name('services_packages.services.details');

Route::get('services_packages/archive', 'ServicesPackagesController@servicePackageArchive')->name('services_packages.archive');
Route::delete('services_packages/force-delete/{servicePackage}', 'ServicesPackagesController@forceDelete')->name('services_packages.force_delete');
Route::put('services_packages/restore-delete/{servicePackage}', 'ServicesPackagesController@restoreDelete')->name('services_packages.restore_delete');
Route::post('services_packages-archiveSelected', 'ServicesPackagesController@archiveSelected')->name('services_packages.archiveSelected');
Route::post('services_packages-restoreSelected', 'ServicesPackagesController@restoreSelected')->name('services_packages.restoreSelected');
