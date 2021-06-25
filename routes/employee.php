<?php

//employee settings
Route::get('/employee_settings', 'EmployeeSettingsController@index')->name('employee_settings.index');
Route::get('/employee_settings/create', 'EmployeeSettingsController@create')->name('employee_settings.create');
Route::post('/employee_settings/store', 'EmployeeSettingsController@store')->name('employee_settings.store');
Route::get('/employee_settings/{employeeSetting}', 'EmployeeSettingsController@edit')->name('employee_settings.edit');
Route::put('/employee_settings/{employeeSetting}', 'EmployeeSettingsController@update')->name('employee_settings.update');
Route::delete('/employee_settings/{employeeSetting}', 'EmployeeSettingsController@destroy')->name('employee_settings.destroy');
Route::get('/employeeSetting/getShiftsByBranch', 'EmployeeSettingsController@getShiftsByBranch');
Route::post('employee_settings/delete_selected' ,'EmployeeSettingsController@deleteSelected')->name('employee_settings.deleteSelected');


//employee data
Route::get('/employees_data', 'EmployeesDataController@index')->name('employees_data.index');
Route::get('employees_data/get-select' ,'EmployeesDataController@getEmployees')->name('get-employees-select');
Route::get('/employees_data/create', 'EmployeesDataController@create')->name('employees_data.create');
Route::post('/employees_data/store', 'EmployeesDataController@store')->name('employees_data.store');
Route::get('/employees_data/{employeeData}', 'EmployeesDataController@edit')->name('employees_data.edit');
Route::put('/employees_data/{employeeData}', 'EmployeesDataController@update')->name('employees_data.update');
Route::delete('/employees_data/{employeeData}', 'EmployeesDataController@destroy')->name('employees_data.destroy');
Route::get('/getEmpSettingByBranch', 'EmployeesDataController@getEmpSettingByBranch');
Route::get('/viewCv', 'EmployeesDataController@viewCv');
Route::post('employees_data/delete_selected' ,'EmployeesDataController@deleteSelected')->name('employees_data.deleteSelected');

//employees attendance
Route::get('/employees_attendance', 'EmployeeAttendanceController@index')->name('employees_attendance.index');
Route::get('/employees_attendance/create', 'EmployeeAttendanceController@create')->name('employees_attendance.create');
Route::post('/employees_attendance/store', 'EmployeeAttendanceController@store')->name('employees_attendance.store');
Route::get('/employees_attendance/{employeeAttendance}', 'EmployeeAttendanceController@edit')->name('employees_attendance.edit');
Route::put('/employees_attendance/{employeeAttendance}', 'EmployeeAttendanceController@update')->name('employees_attendance.update');
Route::delete('/employees_attendance/{employeeAttendance}', 'EmployeeAttendanceController@destroy')->name('employees_attendance.destroy');
Route::get('/getEmpByBranch', 'EmployeeAttendanceController@getEmpByBranch');
Route::post('employees_attendance/delete_selected' ,'EmployeeAttendanceController@deleteSelected')->name('employees_attendance.deleteSelected');

//employee Delay
Route::get('/employees_delay', 'EmployeeDelayController@index')->name('employees_delay.index');
Route::get('/employees_delay/create', 'EmployeeDelayController@create')->name('employees_delay.create');
Route::post('/employees_delay/store', 'EmployeeDelayController@store')->name('employees_delay.store');
Route::get('/employees_delay/{employeeDelay}', 'EmployeeDelayController@edit')->name('employees_delay.edit');
Route::put('/employees_delay/{employeeDelay}', 'EmployeeDelayController@update')->name('employees_delay.update');
Route::delete('/employees_delay/{employeeDelay}', 'EmployeeDelayController@destroy')->name('employees_delay.destroy');
Route::post('employees_delay/delete_selected' ,'EmployeeDelayController@deleteSelected')->name('employees_delay.deleteSelected');


//employee Delay
Route::get('/employee_reward_discount', 'EmployeeRewardDiscountController@index')->name('employee_reward_discount.index');
Route::get('/employee_reward_discount/create', 'EmployeeRewardDiscountController@create')->name('employee_reward_discount.create');
Route::post('/employee_reward_discount/store', 'EmployeeRewardDiscountController@store')->name('employee_reward_discount.store');
Route::get('/employee_reward_discount/{employeeRewardDiscount}', 'EmployeeRewardDiscountController@edit')->name('employee_reward_discount.edit');
Route::put('/employee_reward_discount/{employeeRewardDiscount}', 'EmployeeRewardDiscountController@update')->name('employee_reward_discount.update');
Route::delete('/employee_reward_discount/{employeeRewardDiscount}', 'EmployeeRewardDiscountController@destroy')->name('employee_reward_discount.destroy');
Route::post('employee_reward_discount/delete_selected' ,'EmployeeRewardDiscountController@deleteSelected')->name('employee_reward_discount.deleteSelected');


//employee advances
Route::get('/advances', 'AdvancesController@index')->name('advances.index');
Route::get('/advances/create', 'AdvancesController@create')->name('advances.create');
Route::post('/advances/store', 'AdvancesController@store')->name('advances.store');
Route::get('/advances/{advances}', 'AdvancesController@edit')->name('advances.edit');
Route::put('/advances/{advances}', 'AdvancesController@update')->name('advances.update');
Route::delete('/advances/{advances}', 'AdvancesController@destroy')->name('advances.destroy');
Route::get('/checkMaxAdvance', 'AdvancesController@checkMaxAdvance');
Route::get('/printAdvances', 'AdvancesController@show');
Route::post('advances/delete_selected' ,'AdvancesController@deleteSelected')->name('advances.deleteSelected');


//employee salaries
Route::get('/employees_salaries', 'EmployeeSalariesController@index')->name('employees_salaries.index');
Route::get('/employees_salaries/create', 'EmployeeSalariesController@create')->name('employees_salaries.create');
Route::post('/employees_salaries/store', 'EmployeeSalariesController@store')->name('employees_salaries.store');
Route::get('/employees_salaries/{employeeSalary}', 'EmployeeSalariesController@edit')->name('employees_salaries.edit');
Route::put('/employees_salaries/{employeeSalary}', 'EmployeeSalariesController@update')->name('employees_salaries.update');
Route::delete('/employees_salaries/{employeeSalary}', 'EmployeeSalariesController@destroy')->name('employees_salaries.destroy');
Route::post('employees_salaries/employee-data' ,'EmployeeSalariesController@employeeData')->name('employees_salaries.employee-data');
Route::post('employees_salaries/delete_selected' ,'EmployeeSalariesController@deleteSelected')->name('employees_salaries.deleteSelected');

//employee absence
Route::resource('employee-absence' ,'EmployeeAbsenceCont');
Route::delete('employee-absence/delete/{employee_absence}' ,'EmployeeAbsenceCont@destroy')->name('employee-absence.delete');
Route::post('employee-absence/delete_selected' ,'EmployeeAbsenceCont@deleteSelected')->name('employee-absence.delete_selected');

//employee salary payments
Route::resource('employee-salary-payments' ,'EmployeeSalaryPayments');
Route::post('employee-salary-payments/delete_selected' ,'EmployeeSalaryPayments@deleteSelected')->name('employee-salary-payments.deleteSelected');