<?php
//currencies
Route::get('/currencies', 'CurrenciesController@index')->name('currencies.index');
Route::get('/currencies/create', 'CurrenciesController@create')->name('currencies.create');
Route::post('/currencies/store', 'CurrenciesController@store')->name('currencies.store');
Route::get('/currencies/edit/{currency}', 'CurrenciesController@edit')->name('currencies.edit');
Route::put('/currencies/{currency}', 'CurrenciesController@update')->name('currencies.update');
Route::delete('/currencies/delete/{currency}', 'CurrenciesController@destroy')->name('currencies.destroy');
Route::post('/currencies/delete-selected', 'CurrenciesController@deleteSelected')->name('currencies.deleteSelected');

//countries
Route::get('/countries', 'CountriesController@index')->name('countries.index');
Route::get('/countries/create', 'CountriesController@create')->name('countries.create');
Route::post('/countries/store', 'CountriesController@store')->name('countries.store');
Route::get('/countries/edit/{country}', 'CountriesController@edit')->name('countries.edit');
Route::put('/countries/{country}', 'CountriesController@update')->name('countries.update');
Route::delete('/countries/delete/{country}', 'CountriesController@destroy')->name('countries.destroy');
Route::post('/countries/delete-selected', 'CountriesController@deleteSelected')->name('countries.deleteSelected');

//cities
Route::get('/cities', 'CitiesController@index')->name('cities.index');
Route::get('/cities/create', 'CitiesController@create')->name('cities.create');
Route::post('/cities/store', 'CitiesController@store')->name('cities.store');
Route::get('/cities/edit/{city}', 'CitiesController@edit')->name('cities.edit');
Route::put('/cities/{city}', 'CitiesController@update')->name('cities.update');
Route::delete('/cities/delete/{city}', 'CitiesController@destroy')->name('cities.destroy');
Route::post('/cities/delete-selected', 'CitiesController@deleteSelected')->name('cities.deleteSelected');

//areas
Route::get('/areas', 'AreasController@index')->name('areas.index');
Route::get('/areas/create', 'AreasController@create')->name('areas.create');
Route::post('/areas/store', 'AreasController@store')->name('areas.store');
Route::get('/areas/edit/{area}', 'AreasController@edit')->name('areas.edit');
Route::put('/areas/{area}', 'AreasController@update')->name('areas.update');
Route::delete('/areas/delete/{area}', 'AreasController@destroy')->name('areas.destroy');
Route::post('/areas/delete-selected', 'AreasController@deleteSelected')->name('areas.deleteSelected');

//branches
Route::get('/branches', 'BranchesController@index')->name('branches.index');
Route::get('/branches/create', 'BranchesController@create')->name('branches.create');
Route::post('/branches/store', 'BranchesController@store')->name('branches.store');
Route::get('/branches/edit/{branch}', 'BranchesController@edit')->name('branches.edit');
Route::put('/branches/{branch}', 'BranchesController@update')->name('branches.update');
Route::delete('/branches/delete/{branch}', 'BranchesController@destroy')->name('branches.destroy');
Route::post('/branches/delete-selected', 'BranchesController@deleteSelected')->name('branches.deleteSelected');

Route::get('/country/cities', 'BranchesController@getCitiesByCountryId')->name('country.cities');
Route::get('/city/areas', 'BranchesController@getAreasByCityId')->name('city.areas');

//Shifts
Route::get('/shifts', 'ShiftsController@index')->name('shifts.index');
Route::get('/shifts/create', 'ShiftsController@create')->name('shifts.create');
Route::post('/shifts/store', 'ShiftsController@store')->name('shifts.store');
Route::get('/shifts/edit/{shift}', 'ShiftsController@edit')->name('shifts.edit');
Route::put('/shifts/{shift}', 'ShiftsController@update')->name('shifts.update');
Route::delete('/shifts/delete/{shift}', 'ShiftsController@destroy')->name('shifts.destroy');
Route::post('/shifts/delete-selected', 'ShiftsController@deleteSelected')->name('shifts.deleteSelected');

//taxes
Route::get('/taxes', 'TaxesFeesControllers@index')->name('taxes.index');
Route::get('/taxes/create', 'TaxesFeesControllers@create')->name('taxes.create');
Route::post('/taxes/store', 'TaxesFeesControllers@store')->name('taxes.store');
Route::get('/taxes/edit/{taxesFees}', 'TaxesFeesControllers@edit')->name('taxes.edit');
Route::put('/taxes/{taxesFees}', 'TaxesFeesControllers@update')->name('taxes.update');
Route::delete('/taxes/delete/{taxesFees}', 'TaxesFeesControllers@destroy')->name('taxes.destroy');
Route::post('/taxes/delete-selected', 'TaxesFeesControllers@deleteSelected')->name('taxes.deleteSelected');

//Settings
Route::get('/settings', 'SettingsControllers@edit')->name('settings.edit');
Route::post('/settings/update', 'SettingsControllers@update')->name('settings.update');
