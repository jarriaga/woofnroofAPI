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
    return view('index');
});


Route::get('/reset-password/{token}','ResetPassword\\ResetPasswordController@resetPasswordPage')->name('passwordPage');


Route::group(['prefix'=>'api','middleware'=>'throttle'],function(){

    /*** Route to create a new user account using email and password*/
    Route::post('/signup/email','AuthWoof\\SignupController@createUserEmail')->name('createUserEmail');
    /*** Route for login via email*/
    Route::post('/login/email','AuthWoof\\LoginController@loginEmail')->name('loginEmail');
    /** Route for Forgot your password **/
    Route::post('/forgot-password','AuthWoof\\ForgotPasswordController@createTempToken')->name('forgotPassword');
    /** Route for login/signup via facebook */
    Route::post('/login/facebook','AuthWoof\\LoginController@facebookLogin')->name('loginFacebook');
    /** Route for Login with Token - Refresh token */
    Route::post('/login/token','AuthWoof\\LoginController@loginToken')->name('loginToken');




    /**
     **************************************** Private Area - protected by JWT token
     ***/

    Route::group(['middleware' => 'jwt.auth'],function(){

        // PUT to update user's profile
        Route::put('/profile','Profile\\ProfileController@update')->name('updateProfile');
        // GET user's profile
        Route::get('/profile','Profile\\ProfileController@getProfile')->name('getProfile');
    });



});


