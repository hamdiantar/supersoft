<?php
Route::get('quotations-requests', 'QuotationRequestsController@index')->name('quotations.requests.index');
Route::post('quotations-requests/accept','QuotationRequestsController@accept')->name('quotations.requests.accept');
Route::post('quotations-requests/reject','QuotationRequestsController@reject')->name('quotations.requests.reject');
Route::delete('quotations-requests/{quotationRequest}/delete','QuotationRequestsController@destroy')->name('quotations.requests.destroy');
Route::post('quotations-requests/delete-selected','QuotationRequestsController@deleteSelected')->name('quotations.requests.delete.selected');
