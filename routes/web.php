<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/authenticate','AuthenticationController@authenticate')->name('authenticate');



Route::group(['prefix'=>'api','middleware'],function(){

    /**
     * Route to create a new user account using email and password
     */

    Route::post('/signup/email','AuthWoof\\SignupController@createUser')->name('createUser');





    /**
     * Private Area - protected by JWT token
     ***/

    Route::group(['middleware' => 'jwt.auth'],function(){
        Route::get('/private-zone','PrivateAreaController@showIndex')->name('showIndex');
    });



});


