<?php
Route::post('/parts-common-filter/data-by-branch', 'PartsCommonFilterController@dataByBranch')->name('parts.common.filter.data.by.branch');
Route::post('/parts-common-filter/data-by-main-type', 'PartsCommonFilterController@dataByMainType')->name('parts.common.filter.data.by.main.type');
Route::post('/parts-common-filter/data-by-sub-type', 'PartsCommonFilterController@dataBySubType')->name('parts.common.filter.data.by.sub.type');
