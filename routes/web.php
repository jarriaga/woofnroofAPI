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

use App\News;

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

        /**  PUT to update user's profile **/
        Route::put('/profile','Profile\\ProfileController@update')->name('updateProfile');
        /** GET user's profile **/
        Route::get('/profile','Profile\\ProfileController@getProfile')->name('getProfile');
        /**  GET all news **/
        Route::get('/news','News\\NewsController@getAllNews')->name('getAllNews');
    });


    Route::get('/create-new',function(){
        $new = new News;
        $new->title = 'Delicious treats only $3';
        $new->content = "bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla
		bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla
		bla bla bla bla bla bla bla bla blabla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla
		bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla<br><br><br>
		bla bla bla bla bla bla bla bla blabla bla bla bla bla bla bla bla blabla bla bla bla bla bla bla bla bla
		bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla";
        $new->image = "/images/news/news-1.jpg";
        $new->thumb = "/images/news/thumb/news-1.jpg";
        $new->type = News::SHOPPING;
        $new->icon = "/images/news/icons/".News::$typesIcons[News::SHOPPING];
        $new->status = News::STATUS_ENABLE;
        $new->date = \Carbon\Carbon::now()->toDateTimeString();
        $new->save();
        return $new;
    });


});


