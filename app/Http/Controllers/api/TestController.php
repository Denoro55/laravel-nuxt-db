<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

class TestController extends Controller
{

    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    public function index(Request $request)
    {
        return $token;

////        $this->validateLogin($request);
//
//        if (method_exists($this, 'hasTooManyLoginAttempts') &&
//            $this->hasTooManyLoginAttempts($request)) {
//            $this->fireLockoutEvent($request);
//
//            return response()->json([
//                'success' => false,
//                'errors' => [
//                    'You have been locked out'
//                ]
//            ]);
//        }
//
//        $this->incrementLoginAttempts($request);
//
//        try {
//            if (!$token = $this->auth->attempt($request->only('email', 'password'))){
//                return response()->json([
//                    'success' => false,
//                    'errors' => [
//                        'email' => [
//                            'Invalid email or password'
//                        ]
//                    ]
//                ], 422);
//            }
//        } catch (JWTException $e) {
//            return response()->json([
//                'success' => false,
//                'errors' => [
//                    'email' => [
//                        'Invalid email or password'
//                    ]
//                ]
//            ], 422);
//        }
//
//        return response()->json([
//            'success' => true,
//            'data' => $request->user(),
//            'token' => $token
//        ], 200);

//        return $this->sendFailedLoginResponse($request);
    }
}
