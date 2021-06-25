<?php

use App\AccountingModule\Controllers\CostCenterCont;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => [
    'localeSessionRedirect',
    'localizationRedirect',
    'localeViewPath']
], function () {
    Route::group(['middleware' => ['web','CheckAdmin','auth']], function () {
        Route::get('accounts-tree' ,'AccountsTree@index')->name('accounts-tree-index');

        Route::get('create-tree-form' ,'AccountsTree@createTreeForm')->name('create-tree-form');
        Route::post('create-tree-form' ,'AccountsTree@postCreateTreeForm')->name('post-create-tree-form');

        Route::get('edit-tree-form' ,'AccountsTree@editTreeForm')->name('edit-tree-form');
        Route::post('edit-tree-form/{tree_id}' ,'AccountsTree@postEditTreeForm')->name('post-edit-tree-form');

        Route::get('delete-account-tree' ,'AccountsTree@destroy')->name('delete-account-tree');
        Route::get('deleteable-account-tree' ,'AccountsTree@ableToDelete')->name('deleteable-account-tree');

        Route::get('account-guide' ,'AccountGuide@index')->name('account-guide-index');

        include 'account-relations.php';

        Route::resource('daily-restrictions' ,'DailyRestrictionCont');
        Route::put('daily-restrictions-account-tree/{daily_restriction}' ,'DailyRestrictionCont@update_for_account_tree')->name('daily-restrictions.update-account-tree');
        Route::get('daily-restrictions-delete/{id}' ,'DailyRestrictionCont@delete')->name('daily-restrictions.delete');
        Route::get('daily-restrictions-print/{id}' ,'DailyRestrictionCont@print')->name('daily-restrictions.print');

        Route::get('cost-centers' ,'CostCenterCont@index')->name('cost-centers.index');
        Route::get('delete-cost-center' ,'CostCenterCont@delete')->name('cost-centers.delete');
        Route::get('delete-ability-cost-center' ,'CostCenterCont@ableToDelete')->name('cost-centers.delete-ability');
        Route::get('cost-center/{action_for}' ,'CostCenterCont@loadForm')->name('cost-centers.form');
        Route::post('cost-centers-edit' ,'CostCenterCont@update')->name('cost-centers.edit');
        Route::post('cost-centers-create' ,'CostCenterCont@store')->name('cost-centers.create');
        Route::get('cost-center-get-branches' ,'CostCenterCont@get_cost_center_branches')->name('cost-center-get-branches');

        Route::get('general-ledger' ,'GeneralLedgerReport@index')->name('accounting-general-ledger');
        Route::get('gl-load-account-tree' ,'GeneralLedgerReport@load_account_tree')->name('load-account-tree');
        Route::get('gl-load-cost-tree' ,'GeneralLedgerReport@load_center_tree')->name('load-cost-tree');

        Route::resource('fiscal-years' ,'FiscalYearCont');
        Route::get('fiscal-years-change-status/{id}' ,'FiscalYearCont@changeStatus')->name('fiscal-years.change-status');
        Route::get('fiscal-years-delete/{id}' ,'FiscalYearCont@delete')->name('fiscal-years.delete');
        Route::get('fiscal-years-check-available' ,'FiscalYearCont@check_available_date')->name('fiscal-years.check-available');

        Route::get('update-accounts-tree-codes' ,function() {
            \App\AccountingModule\Models\AccountsTree::update_tree_codes();
        });

        Route::get('trial-balance' ,'TrialBalance@index')->name('trial-balance-index');
        Route::get('balance-sheet' ,'BalanceSheet@index')->name('balance-sheet-index');
        Route::get('income-list' ,'IncomeList@index')->name('income-list-index');
        Route::get('trading-account' ,'TradingAccount@index')->name('trading-account-index');

        Route::get('adverse-restrictions' ,'AdverseRestriction@form')->name('adverse-restrictions');
        Route::post('adverse-restrictions' ,'AdverseRestriction@save')->name('adverse-restrictions-save');
        Route::post('adverse-restrictions-store' ,'AdverseRestriction@store')->name('adverse-restrictions-store');

        Route::get('get-cost-centers-by-branch' ,function () {
            return response(['options' => CostCenterCont::build_centers_options(NULL ,NULL ,1 ,true ,$_GET['branch_id'])]);
        })->name('branch-cost-center');
    });
    Route::get('show-message' ,'AccountGuide@show_message')->name('show-message');
});