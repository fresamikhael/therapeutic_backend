<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'username' => [
                    'required'
                ],
                'password' => [
                    'required'
                ],
            ]
        );

        if ($validate->fails()) {
            return response()->json(
                [
                    'meta' => [
                        'status' => 'error',
                        'message' => 'Validation Error',
                    ],
                    'data' => [
                        'validation_errors' => $validate->errors()
                    ],
                ]
            );
        } else {
            $user = User::where('username', $request->username)
                ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(
                    [
                        'meta' => [
                            'code' => 401,
                            'status' => 'error',
                            'message' => 'Authentication Failed',
                        ],
                        'data' => [
                            'message' => 'Unauthorized'
                        ],
                    ]
                );
            }

            if ($user->roles === 'ADMIN') {
                $token = $user->createToken('authToken')->plainTextToken;
            } elseif ($user->roles === 'THERAPIST') {
                $token = $user->createToken('authToken')->plainTextToken;
            } elseif ($user->roles === 'USER') {
                $token = $user->createToken('authToken')->plainTextToken;
            }


            return response()->json(
                [
                    'meta' => [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Login Success',
                    ],
                    'data' => [
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                        'user' => $user,
                    ],
                ]
            );
        }
    }
}
