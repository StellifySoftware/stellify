<?php
//auth
Auth::routes(['verify' => false, 'reset' => true]);
//reserved system routes
Route::group(['middleware' => 'web'], function () {
    Route::get('/deleteAll', 'Stellisoft\Stellify\Http\Controllers\UtilityController@deleteAll');
    Route::get('/deleteBlock', 'Stellisoft\Stellify\Http\Controllers\UtilityController@deleteBlock');
    Route::get('/deleteBlocks', 'Stellisoft\Stellify\Http\Controllers\UtilityController@deleteBlocks');
    Route::get('/moveBlock', 'Stellisoft\Stellify\Http\Controllers\UtilityController@move');
    Route::get('/moveSubblock', 'Stellisoft\Stellify\Http\Controllers\UtilityController@moveSub');
    Route::post('/saveBlock', 'Stellisoft\Stellify\Http\Controllers\UtilityController@saveBlock');
    Route::get('/insertBlock', 'Stellisoft\Stellify\Http\Controllers\UtilityController@insertBlock');
    Route::get('/insertDuplicateBlock', 'Stellisoft\Stellify\Http\Controllers\UtilityController@insertDuplicateBlock');
    Route::get('/insertSubBlock', 'Stellisoft\Stellify\Http\Controllers\UtilityController@insertSubBlock');
    Route::get('/createSubBlock', 'Stellisoft\Stellify\Http\Controllers\UtilityController@createSubBlock');
    Route::get('/deleteSubBlock', 'Stellisoft\Stellify\Http\Controllers\UtilityController@deleteSubBlock');
});
//catch all
Route::get('{all}', '\Stellisoft\Stellify\Http\Controllers\PagesController@index')->where('all', '.*')->middleware('web');
