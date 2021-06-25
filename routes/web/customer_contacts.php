<?php


Route::post('customer/new-contact', 'CustomerContactsController@newContact')->name('customer.new.contact');
Route::post('customer/update-contact', 'CustomerContactsController@update')->name('customer.contact.update');
Route::post('customer/destroy-contact', 'CustomerContactsController@destroy')->name('customer.contact.destroy');
