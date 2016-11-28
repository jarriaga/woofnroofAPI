<?php
/**
 * Created by PhpStorm.
 * User: jbarron
 * Date: 9/6/16
 * Time: 4:24 PM
 */

namespace App\Http\Controllers\AuthWoof;


use App\Http\Controllers\Controller;
use App\User;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Response;
use Ramsey\Uuid\Uuid;

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

	/**
	 * This Method receive the facebook access token in order to log in or signup a new user using facebook login
	 * workflow
	 * @param Request $request
	 * @return mixed
	 */
	public function facebookLogin(Request $request)
	{
		$validator = Validator::make($request->only('facebookToken'),['facebookToken'=>'required']);
		if($validator->fails()){
			return response()->json(['error'=>$validator->errors()],Response::HTTP_BAD_REQUEST);
		}
		$facebookToken = $request->input('facebookToken');
		//create the Facebook object with the facebook token
		$fb = new Facebook(
			[
				'app_id' => env('FB_APP_ID'),
				'app_secret'=>env('FB_SECRET'),
				'default_access_token'=>$facebookToken,
				'default_graph_version' => 'v2.7'
			]);

		$request = $fb->request('GET','/me?fields=id,name,email');

		try {
			$response = $fb->getClient()->sendRequest($request);
		} catch(FacebookResponseException $e) {
			// When Graph returns an error
			$error = 'Graph returned an error: ' . $e->getMessage();
			return response()->json(compact('error'),Response::HTTP_UNAUTHORIZED);
		} catch(FacebookSDKException $e) {
			// When validation fails or other local issues
			$error = $e->getMessage();
			return response()->json(compact('error'),Response::HTTP_UNAUTHORIZED);
		}
		//Test user token EAAZAgQWSc9R8BAJYeWAZA8Xu43vRuLFElHGVKRi7KdcNqBOYcVut0LFTUv5bpDDKfb380bvIEVTQIAEqGogsgh66P06e0BSdc8ERsO67qNZAFFlLdiIDyQBcG4wY55DBIhhlbOEBUtbrWs83WzPeBA5tPPk8u5njlMRSPKun92z8uys4skM
		try{
			$response = $this->signUpAndLoginFacebook($response->getGraphNode());
		}catch(\Exception $e){
			$error = $e->getMessage();
			return response()->json(compact('error'),Response::HTTP_BAD_REQUEST);
		}
		return response()->json($response,200);
	}


	/**
	 * Private method to created and request the token for the app
	 * @param $userFacebookData
	 * @return array
	 */
	private function signUpAndLoginFacebook($userFacebookData)
	{
		//Search first user with that facebook id
		$user = User::where('facebookId',$userFacebookData['id'])->first();
		//TODO if not exists then create new account and return the token
		if(!$user){

				$user = User::create([
					'uuid' => Uuid::uuid4()->toString(),
					'facebookId' => $userFacebookData['id'],
					'name' => $userFacebookData['name'],
					'email' => ($userFacebookData['email']) ? $userFacebookData['email'] : null
				]);

		}
		$token = JWTAuth::fromUser($user);
		$orange = 1;
		if(!$user->mobile )
			$orange = 0;
		return array_merge(compact('token'),['orange-info'=>$orange]);
	}


	/**
	 * This method receive a stored token and return a new refresh token
	 * @param Request $request
	 * @return mixed
	 */
	public function loginToken(Request $request)
	{
		$token = JWTAuth::getToken();
		if(!$token){
			return response()->json(['error'=>'Token not provided'],Response::HTTP_FORBIDDEN);
		}
		try{
			$token = JWTAuth::refresh($token);
		}catch(TokenInvalidException $e){
			return response()->json(['error'=>'The token is invalid'],Response::HTTP_UNAUTHORIZED);

		}
		//get the user by token
		$user = JWTAuth::toUser($token);

		//check if the orange screen should be showed up
		$orange = 1;
		if( !$user->mobile )
			$orange = 0;
		return response()->json(array_merge(compact('token'),['orange-info'=>$orange]));
	}
}