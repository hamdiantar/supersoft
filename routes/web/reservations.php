<?php

Route::get('reservations' ,'Reservations@index')->name('reservations.index');
Route::post('reservations' ,'Reservations@store')->name('reservations.store');
Route::get('reservations/{id}' ,'Reservations@fetchEvent')->name('reservations.show');
Route::put('reservations/{id}' ,'Reservations@updateEvent')->name('reservations.update');