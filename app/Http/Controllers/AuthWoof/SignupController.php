<?php

namespace App\Http\Controllers\AuthWoof;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Created by PhpStorm.
 * User: jbarron
 * Date: 9/6/16
 * Time: 1:07 AM
 */
class SignupController extends \App\Http\Controllers\Controller
{

	public function createUserEmail(Request $request)
	{
		//Validate parameters
		$validator = Validator::make($request->all(), [
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|min:6|confirmed',
			'mobile'=>'required'
		]);

		//Check if validation fails
		if($validator->fails()){
			return response()->json(['error'=>$validator->errors()],Response::HTTP_BAD_REQUEST);
		}

		//Create new user and return response
		try{
			return User::create([
				'name' => '',
				'email' => $request->input('email'),
				'password' => Hash::make( $request->input('password')),
				'mobile' => $request->input('mobile')
			]);
		}catch(\Exception $e){
			// an Error was produced
			return response()->json(['error'=>'something is wrong'],Response::HTTP_BAD_REQUEST);
		}

	}

	
}