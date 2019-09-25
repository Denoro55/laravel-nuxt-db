<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'home';
    protected $auth;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
//        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return response()->json([
                'success' => false,
                'errors' => [
                    'You have been locked out'
                ]
            ]);
        }

        $this->incrementLoginAttempts($request);

        try {
            if (!$token = $this->auth->attempt($request->only('email', 'password'))){
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'email' => [
                            'Invalid email or password'
                        ]
                    ]
                ], 422);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'email' => [
                        'Invalid email or password'
                    ]
                ]
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => $request->user(),
            'token' => $token
        ], 200);

//        return $this->sendFailedLoginResponse($request);
    }
}
