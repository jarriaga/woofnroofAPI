<?php
/**
 * Created by PhpStorm.
 * User: jbarron
 * Date: 9/6/16
 * Time: 4:24 PM
 */

namespace App\Http\Controllers\AuthWoof;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Response;

class LoginController extends Controller
{


	/**
	 * Method to log in user via Email and password
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function loginEmail(Request $request)
	{
		$credentials	=	$request->only('email','password');

		//Validate parameters
		$validator = Validator::make($credentials, [
			'email' => 'required|email|max:255',
			'password' => 'required|min:6',
		]);

		//Check if validation fails
		if($validator->fails()){
			return response()->json(['error'=>$validator->errors()],Response::HTTP_BAD_REQUEST);
		}

		try {
			// attempt to verify the credentials and create a token for the user
			if (! $token = JWTAuth::attempt($credentials)) {
				return response()->json(['error' => 'The email or password you entered is incorrect'], 401);
			}
		} catch (JWTException $e) {
			// something went wrong whilst attempting to encode the token
			return response()->json(['error' => 'could_not_create_token'], 500);
		}
		$result = compact('token');
		$user = JWTAuth::toUser($result['token']);
		$orange = 1;
		if(!$user->birthday  || !$user->mobile )
			$orange = 0;
		// all good so return the token
		return response()->json(array_merge(compact('token'),['orange-info'=>$orange]));
	}
	
}