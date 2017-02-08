<?php
/**
 * Created by PhpStorm.
 * User: jbarron
 * Date: 9/13/16
 * Time: 9:36 PM
 */

namespace App\Http\Controllers\Authv2;


use App\Http\Controllers\Controller;
use App\Notifications\ForgotPassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{

	/**
	 * Method to create a valid token in order to execute the reset your password webpage
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function createTempToken(Request $request)
	{
		$validator = Validator::make($request->all(),['email' => 'required|email|max:255']);
		//Validate the email before send the email token data
		if($validator->fails()){
			return response()->json(['error'=>$validator->errors()],Response::HTTP_BAD_REQUEST);
		}
		$user = User::where('email',$request->input('email'))->first();
		//if the user doesn't exist
		if(!$user){
			return response()->json(['error'=>'Something is wrong'],Response::HTTP_BAD_REQUEST);
		}
		//Generate a new token
		$user->token = str_random(15);
		$user->save();
		//send a new notification(email) through app/Notifications/
		$user->notify(new ForgotPassword($user));
		return response()->json(['success'=>'The email recovery instructions was sent'],Response::HTTP_OK);
	}
	
}