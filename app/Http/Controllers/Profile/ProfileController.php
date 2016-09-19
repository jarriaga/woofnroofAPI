<?php
/**
 * Created by PhpStorm.
 * User: jbarron
 * Date: 9/6/16
 * Time: 9:35 PM
 */

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileController	extends Controller
{

	/**
	 * Method to update the user's profile based on the JWT token
	 * @param Request $request
	 */
	public function update(Request $request)
	{
		//request the token and convert to User
		$user = JWTAuth::toUser($request->input('token'));
		if(!$user){
			return response()->json(['error'=>'The user doesn\'t exist'],Response::HTTP_BAD_REQUEST);
		}

		try{
			//Save user
			$user->name			=	$request->input('name',$user->name);
			$user->facebookId	=	$request->input('facebookId',$user->facebookId);
			$user->birthday		=	$request->input('birthday',$user->birthday);
			$user->latitude		=	$request->input('latitude',$user->latitude);
			$user->longitude	=	$request->input('longitude',$user->longitude);
			$user->mobile		=	$request->input('mobile',$user->mobile);
			$user->save();
			return response()->json(['success'=>'the profile was updated'],Response::HTTP_OK);

		}catch(\Exception $e){
			return response()->json(['error'=>$e->getMessage()],Response::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * This method return the user's profile given the JWT token
	 * @param Request $request
	 * @return mixed
	 */
	public function getProfile(Request $request)
	{
		$user = JWTAuth::toUser($request->input('token'));
		if(!$user){
			return response()->json(['error'=>'The user doesn\'t exist'],Response::HTTP_BAD_REQUEST);
		}

		return $user;
	}

}