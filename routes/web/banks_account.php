<?php

Route::post('suppliers/new-bank-account', 'SupplierBankAccountController@newBankAccount')->name('suppliers.new.bank-account');
Route::post('suppliers/update-bank-account', 'SupplierBankAccountController@update')->name('suppliers.bank-account.update');
Route::post('suppliers/destroy-bank-account', 'SupplierBankAccountController@destroy')->name('suppliers.bank-account.destroy');
