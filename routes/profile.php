<?php

Route::get('/profile', 'ProfileController@index');
Route::put('/profile/update/{user}', 'ProfileController@update')->name('profile.update');
