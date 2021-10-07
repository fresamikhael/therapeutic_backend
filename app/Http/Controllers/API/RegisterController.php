<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => [
                    "required",
                    "string",
                    "regex:/^['\p{L}\s-]+$/u"
                ],
                'username' => [
                    'required',
                    'string',
                    Rule::unique(User::class),
                ],
                'phone_number' => [
                    'required',
                    'numeric',
                    Rule::unique(User::class),
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    Rule::unique(User::class),
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8',
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
        }

        try {

            $user = User::create(
                [
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'roles' => 'USER',
                    'password' => Hash::make($request->password),
                ]
            );

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json(
                [
                    'meta' => [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Registered Successfully',
                    ],
                    'data' => [
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                        'user' => $user
                    ],
                ]
            );
        } catch (QueryException $e) {
            return response()->json(
                [
                    'meta' => [
                        'code' => 500,
                        'status' => 'error',
                        'message' => 'Error',
                    ],
                    'data' => [
                        'message' => 'Failed' . $e
                    ],
                ]
            );
        }
    }
}
