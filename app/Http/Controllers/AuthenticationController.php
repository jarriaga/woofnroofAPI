<?php
/**
 * Created by PhpStorm.
 * User: jbarron
 * Date: 9/6/16
 * Time: 12:48 AM
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationController extends Controller
{
	/**
	 * Function to authenticate a user given email and password
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function authenticate(Request $request)
	{
		$credentials	=	$request->only('email','password');

		try {
			// attempt to verify the credentials and create a token for the user
			if (! $token = JWTAuth::attempt($credentials)) {
				return response()->json(['error' => 'invalid_credentials'], 401);
			}
		} catch (JWTException $e) {
			// something went wrong whilst attempting to encode the token
			return response()->json(['error' => 'could_not_create_token'], 500);
		}

		// all good so return the token
		return response()->json(compact('token'));
	}

}