<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['user'],'namespace' => 'User','prefix' => 'user'], function() {

    Route::get('dashboard','HomeController@dashboard');
    Route::resource('profile','ProfileController')->middleware('profile_check');
    Route::get('noti/{id}','HomeController@noti');

    Route::group(['middleware' => 'cash_in_out_check'], function() {
        Route::get('cash_in_history/{id}','PaymentController@cash_in_history');
        Route::get('cash_out_history/{id}','PaymentController@cash_out_history');
    });

    Route::post('save_token','HomeController@save_token');

});
