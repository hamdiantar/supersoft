<?php

Route::resource('points-rules', 'PointsRulesController');
Route::post('points-rules-deleteSelected', 'PointsRulesController@deleteSelected')->name('points.rules.deleteSelected');
