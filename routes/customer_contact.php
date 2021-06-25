<?php

Route::post('customers/new-contact', 'CustomerContactsController@newContact')->name('customers.new.contact');
Route::post('customers/update-contact', 'CustomerContactsController@update')->name('customers.contact.update');
Route::post('customers/destroy-contact', 'CustomerContactsController@destroy')->name('customers.contact.destroy');
