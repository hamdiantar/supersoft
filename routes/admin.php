<?php

use Illuminate\Support\Facades\Auth;


Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => [
    'localeSessionRedirect',
    'localizationRedirect',
    'localeViewPath']
], function () {


    Route::group(['middleware' => [], 'as' => 'admin:', 'prefix' => 'admin', 'namespace' => 'Admin'], function () {

        Auth::routes();
    });

//CheckAdmin

    Route::group(['middleware' => ['web','CheckAdmin','auth'], 'as' => 'admin:', 'prefix' => 'admin', 'namespace' => 'Admin'], function () {

        Route::get('autocomplete','AjaxController@AutoComplete');

        Route::get('/', 'DashboardController@index')->name('home');

        include 'setting.php';
        include 'asset.php';
        include 'items-management.php';
        include 'concession_library.php';
        include 'services.php';
        include 'suppliers-groups.php';
        include 'customers.php';
        include 'revenue-expense.php';
        include 'lockers-accounts.php';
        include 'purchase-invoices.php';
        include 'sales-invoices.php';
        include 'sales-invoices-return.php';
        include 'purchase-returns.php';
        include 'quotations.php';
        include 'employee.php';
        include 'work-cards.php';
        include 'assets.php';
        include 'capital-balance.php';
        include 'companies.php';
        include 'carModles.php';
        include 'CarTypes.php';
        include 'profile.php';
        include 'work-card-sample.php';
        include 'customers_requests.php';
        include 'quotation-requests.php';
        include 'purchase_quotations.php';
        include 'supply_terms.php';
        include 'supply_orders.php';
        include 'opening-balance-routes.php';
        include 'purchase_quotations_compare.php';
        include 'purchase_receipts.php';

        include 'notifications.php';
        include 'notification_setting.php';
        include 'mail_setting.php';
        include 'sms_settings.php';
        include 'maintenance_status.php';
        include 'quotationToSales.php';
        include 'point_settings.php';
        include 'points_rules.php';
        include 'customer_cars.php';
        include 'sub_parts_types.php';
        include 'concession_types.php';
        include 'concession.php';
        include 'damaged_stock.php';
        include 'settlements.php';
        include 'parts_common_filter.php';


        include 'stores-transfers.php';
        include 'supplier_contacts.php';
        include 'customer_contact.php';

        include 'money-permissions.php';
        include 'opening-balance-routes.php';
        include 'supplier_bank_account.php';
        include 'purchase_request.php';
        include 'store_employee_history.php';


//      users
        Route::get('users/archive', 'UsersController@index')->name('users.archive');
        Route::delete('users/force-delete/{user}', 'UsersController@forceDelete')->name('users.force_delete');
        Route::put('users/restore-delete/{user}', 'UsersController@restoreDelete')->name('users.restore_delete');
        Route::post('users-archiveSelected', 'UsersController@archiveSelected')->name('users.archiveSelected');
        Route::post('users-restoreSelected', 'UsersController@restoreSelected')->name('users.restoreSelected');
        Route::resource('users', 'UsersController');
        Route::post('users-deleteSelected', 'UsersController@deleteSelected')->name('users.deleteSelected');
        Route::delete('users-deleteActivities/{user}', 'UsersController@deleteActivities')->name('users.deleteActivities');

//      roles
        Route::resource('roles', 'RolesController');
        Route::post('roles-deleteSelected', 'RolesController@deleteSelected')->name('roles.deleteSelected');

//      activity log
        Route::post('activity-log-restoreAllData', 'ActivityLogsController@restoreAllData')->name('activity.restoreAllData');
        Route::post('activity-log-restoreSelected', 'ActivityLogsController@restoreSelected')->name('activity.restoreSelected');
        Route::get('activity-log/archive', 'ActivityLogsController@archive')->name('activity.archive');
        Route::post('activity-log-deleteSelected', 'ActivityLogsController@deleteSelected')->name('activity.deleteSelected');
        Route::get('activity-log', 'ActivityLogsController@index')->name('activity.index');
        Route::delete('activity-log/{activity}', 'ActivityLogsController@delete')->name('activity.delete');
        Route::delete('activity-log', 'ActivityLogsController@deleteAll')->name('activity.delete-all');

        Route::get('db-backup' ,'DB_Backup@index')->name('db-backup');

        Route::get('reservations' ,'Reservations@index')->name('reservations.index');
        Route::get('reservations/{id}' ,'Reservations@getReservation')->name('reservations.get_reservation');
        Route::put('reservations/{id}' ,'Reservations@update')->name('reservations.update');

    });


});

Route::group(['middleware' => ['web','CheckAdmin','auth'], 'as' => 'admin:', 'prefix' => 'admin', 'namespace' => 'Admin'], function () {
    include 'invoice_parts_filter.php';
});
