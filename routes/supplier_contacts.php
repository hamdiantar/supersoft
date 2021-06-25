<?php

Route::post('suppliers/new-contact', 'SupplierContactsController@newContact')->name('suppliers.new.contact');
Route::post('suppliers/update-contact', 'SupplierContactsController@update')->name('suppliers.contact.update');
Route::post('suppliers/destroy-contact', 'SupplierContactsController@destroy')->name('suppliers.contact.destroy');
