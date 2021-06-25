<?php

//new units
Route::post('invoices/sub-parts-types', 'InvoicePartsController@getSubPartsTypes')->name('sub.parts.types');
Route::post('invoices/parts-filter-footer', 'InvoicePartsController@partsFilterFooter')->name('parts-filter-footer');
