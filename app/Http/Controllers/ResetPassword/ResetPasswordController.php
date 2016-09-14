<?php

/**
 * Created by PhpStorm.
 * User: jbarron
 * Date: 9/13/16
 * Time: 10:51 PM
 */

namespace App\Http\Controllers\ResetPassword;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{

	public function resetPasswordPage($token,Request $request)
	{
		$validator = Validator::make(['token'=>$token],['token' => 'required|alpha_num']);
		//Validate the token
		if($validator->fails()){
			return abort(404);
		}
		// Find the user by the reset password token
		$user = User::where('token',$token)->first();
		if(!$user){
			return abort(404);
		}

		return view('resetPassword');
	}
	
}