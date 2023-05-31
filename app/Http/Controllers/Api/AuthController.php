<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Notifications\EmailVerificationNotification;

class AuthController extends Controller
{
   /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        //dd($request);
        $validator = Validator::make($request->all(), [
            'type'       => 'sometimes|required|integer|in:0,1',   // allow customer or seller only
            'first_name' => 'required|string|between:2,100',
            'last_name'  => 'nullable|string|between:2,100',
            'email'      => 'required|max:100|unique:users',
            'phone'      => 'required|string|min:6|unique:users',
            'password'   => 'required|string|confirmed|min:6',
        ]);
        //dd($validator->validated());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
        $user->refresh();

        $user->notify(new EmailVerificationNotification());

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL(),
            'user' => auth()->user()
        ]);
    }
}
