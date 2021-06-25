<?php

Route::post('concession/upload_library', 'ConcessionLibraryController@uploadLibrary')->name('concession.upload_library');
Route::post('concession/library/get-files', 'ConcessionLibraryController@getFiles')->name('concession.library.get.files');
Route::post('concession/library/file-delete', 'ConcessionLibraryController@destroyFile')->name('concession.library.file.delete');
