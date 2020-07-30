<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Transformer\UserTransformer;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ApiController;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(Response $response)
    {
        parent::__construct($response);
        $this->middleware('auth:api', ['except' => ['login','registration']]);
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     operationId="ApiAuthLogin",
     *     tags={"User"},
     *     summary="Login with user credentials",
     *     @OA\Response(
     *         response="200",
     *         description="Successfuly loged you in!"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ApiUserLoginRequest")
     *     )
     * )
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }
    /**
     * @OA\Post(
     *     path="/auth/registration",
     *     operationId="ApiAuthRegistration",
     *     tags={"User"},
     *     summary="Registration of user",
     *     @OA\Response(
     *         response="200",
     *         description="Successfuly loged you on!"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ApiUserRegistrationRequest")
     *     )
     * )
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public $successStatus = 200;

    public function registration(Request $request)
    {
        // dd('test');
    $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
        ]);
        $credentials = request(['name','email', 'password']);

        $user = User::create($credentials);
        $profile = $request->file('profile');
         $profile_img = $user->addMedia($profile)->toMediaCollection('profile');;
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }
    /**
     * @OA\Post(
     *     path="/auth/me",
     *     operationId="ApiAuthMe",
     *     tags={"User"},
     *     summary="Get user-info by token.",
     *     security={
     *       {"token": {}},
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="You have access to use system."
     *     )
     * )
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
      return $this->response->get([
          'user' => [auth()->user(), new UserTransformer]
      ]);
    }

    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     operationId="ApiAuthLogout",
     *     tags={"User"},
     *     summary="Logout user.",
     *     security={
     *       {"token": {}},
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Successfully logged out."
     *     )
     * )
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
      auth()->logout();

      return response()->json([
          'code' => 200,
          'data' => [
              'message' => 'Successfully logged out'
          ]
      ]);
    }

    /**
     * @OA\Post(
     *     path="/auth/refresh",
     *     operationId="ApiAuthRefresh",
     *     tags={"User"},
     *     summary="Refresh user token.",
     *     security={
     *       {"token": {}},
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Token successfully refreshed."
     *     )
     * )
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
      return response()->json([
          'code' => 200,
          'data' => [
              'access_token' => $token,
              'token_type' => 'bearer',
              'expires_in' => auth()->factory()->getTTL() * 60
          ]
      ]);
    }
    
    public function editProfile(Request $request)
    {
      $id = Auth::user()->id;
      $profile = User::find($id);
      if($request->isMethod('post')){
          $data = $request->input();
          // dd($data);
          $profile->update($data);
         $profile_img = $profile->addMedia($request->file('profile'))->toMediaCollection('profile');;
          $save = $profile->save();
             if($save){
              return response()->json([
                  'code' => 200,
                  'data' => [
                      'message' => 'Updated profile!'
                  ]
            ]);
          }else{
              return response()->json([
                  'data' => [
                      'message' => 'Error to updated profile!'
                  ]
            ]);
          }
      }else{
          if($request->isMethod('get')){
              return response()->json([
                  'data' => [
                      'message' => 'This query worked to POST type!'
                  ]
            ]);
          }                
      }
      // dd($id);

  }
}