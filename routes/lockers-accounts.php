<?php
//lockers
Route::resource('lockers', 'LockersController');
Route::post('lockers-deleteSelected', 'LockersController@deleteSelected')->name('lockers.deleteSelected');
Route::get('get-users', 'LockersController@getUsers')->name('lockers.get.users');
Route::get('get-data-by-branch', 'LockersController@dataByBranch')->name('get.data.by.branch');

//accounts
Route::resource('accounts', 'AccountsController');
Route::post('accounts-deleteSelected', 'AccountsController@deleteSelected')->name('accounts.deleteSelected');
Route::get('get-users', 'LockersController@getUsers')->name('lockers.get.users');
Route::get('get-accounts-by-branch', 'AccountsController@dataByBranch')->name('get.accounts.by.branch');

//lockers
Route::resource('lockers-transactions', 'LockersTransactionsController');
Route::post('lockers-transactions-deleteSelected', 'LockersTransactionsController@deleteSelected')
    ->name('lockers.transactions.deleteSelected');
Route::get('get-transactions-by-branch', 'LockersTransactionsController@dataByBranch')->name('get.transactions.by.branch');
Route::get('transaction-form-by-branch', 'LockersTransactionsController@dataByBranchInForm')->name('transaction.form.by.branch');

Route::get('get-locker-balance', 'LockersTransactionsController@getLockerBalance')->name('lockers.get.balance');
Route::get('get-account-balance', 'LockersTransactionsController@getAccountBalance')->name('accounts.get.balance');

//lockers transfer
Route::get('lockers-transfer', 'LockerTransferController@index')->name('lockers-transfer.index');
Route::get('lockers-transfer/{id}', 'LockerTransferController@show')->name('lockers-transfer.show');
// Route::resource('lockers-transfer', 'LockerTransferController');
// Route::post('lockers-transfer-deleteSelected', 'LockerTransferController@deleteSelected')
//     ->name('lockers.transfer.deleteSelected');
Route::get('get-lockers-transfer-by-branch', 'LockerTransferController@dataByBranch')->name('get.lockers.transfer.by.branch');
Route::get('locker-transfer-form-by-branch', 'LockerTransferController@dataByBranchInForm')->name('locker.transfer.form.by.branch');

//accounts transfer
Route::get('accounts-transfer', 'AccountTransferController@index')->name('accounts-transfer.index');
Route::get('accounts-transfer/{id}', 'AccountTransferController@show')->name('accounts-transfer.show');
// Route::resource('accounts-transfer', 'AccountTransferController');
Route::post('accounts-transfer-deleteSelected', 'AccountTransferController@deleteSelected')
    ->name('accounts.transfer.deleteSelected');
Route::get('get-accounts-transfer-by-branch', 'AccountTransferController@dataByBranch')->name('get.accounts.transfer.by.branch');
Route::get('account-transfer-form-by-branch', 'AccountTransferController@dataByBranchInForm')->name('account.transfer.form.by.branch');
