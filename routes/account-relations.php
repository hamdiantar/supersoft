<?php

use Illuminate\Support\Facades\Route;

Route::resource('account-relations' ,'AccountRelations');
Route::get('account-relations-delete/{id}' ,'AccountRelations@delete')->name('account-relations.delete');
Route::get('fetch-accounts-tree-branches' ,'AccountRelations@fetchRootBranches')->name('fetch-accounts-tree-branches');
Route::post('account-relation-unique' ,'AccountRelations@checkRelUnique')->name('check-account-relation-unique');
Route::post('account-relations-store-2' ,'AccountRelations@store_for_locker')->name('account-relations.store2');
Route::put('account-relations-update-2/{account_relation}' ,'AccountRelations@update_for_locker')->name('account-relations.update2');
Route::post('account-permissions-store' ,'AccountRelations@store_permissions_relation')->name('account-relations.permissions-store');
Route::put('account-permissions-update/{id}' ,'AccountRelations@update_permissions_relation')->name('account-relations.permissions-update');
Route::post('account-relations/store-types-items' ,'AccountRelations@store_types_items')->name('account-relations.store-types-items');
Route::post('account-relations/store-money-permissions' ,'AccountRelations@store_money_permission')->name('account-relations.store-money-permissions');
Route::post('account-relations/store-lockers-banks' ,'AccountRelations@store_lockers_banks')->name('account-relations.store-lockers-banks');
Route::post('account-relations/store-customers' ,'AccountRelations@store_customers')->name('account-relations.store-customers');
Route::post('account-relations/store-suppliers' ,'AccountRelations@store_suppliers')->name('account-relations.store-suppliers');
Route::post('account-relations/store-employees' ,'AccountRelations@store_employees')->name('account-relations.store-employees');
Route::post('account-relations/update-customers/{id}' ,'AccountRelations@update_customers')->name('account-relations.update-customers');
Route::post('account-relations/update-employees/{id}' ,'AccountRelations@update_employees')->name('account-relations.update-employees');
Route::post('account-relations/update-suppliers/{id}' ,'AccountRelations@update_suppliers')->name('account-relations.update-suppliers');
Route::post('account-relations/update-money-permissions/{id}' ,'AccountRelations@update_money_permission')->name('account-relations.update-money-permissions');
Route::post('account-relations/update-lockers-banks/{id}' ,'AccountRelations@update_lockers_banks')->name('account-relations.update-lockers-banks');
Route::post('account-relations/update-types-items/{id}' ,'AccountRelations@update_types_items')->name('account-relations.update-types-items');
Route::post('account-relations/store-stores-permissions' ,'AccountRelations@store_stores_permission')->name('account-relations.store-stores-permissions');
Route::post('account-relations/update-stores-permissions/{id}' ,'AccountRelations@update_stores_permission')->name('account-relations.update-stores-permissions');
Route::post('account-relations/store-stores' ,'AccountRelations@store_stores_relation')->name('account-relations.store-stores');
Route::post('account-relations/update-stores/{id}' ,'AccountRelations@update_stores_relation')->name('account-relations.update-stores');
Route::post('account-relations/store-taxes' ,'AccountRelations@store_taxes_related')->name('account-relations.store-taxes');
Route::post('account-relations/update-taxes/{id}' ,'AccountRelations@update_taxes_related')->name('account-relations.update-taxes');
Route::post('account-relations/store-discounts' ,'AccountRelations@store_discounts_related')->name('account-relations.store-discounts');
Route::post('account-relations/update-discounts/{id}' ,'AccountRelations@update_discounts_related')->name('account-relations.update-discounts');