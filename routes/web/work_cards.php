<?php

Route::get('work-cards', 'WorkCardsController@index')->name('work.cards.index');

//print
Route::get('/card-invoices-show', 'WorkCardsController@show')->name('card.invoices.show');

