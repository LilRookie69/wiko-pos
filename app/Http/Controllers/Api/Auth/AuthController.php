<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        $validator = validator(request()->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::where('email', request()->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 401);
        } else if ($user) {
            $token = $user->createToken('token-name')->plainTextToken;
            if (Hash::check(request()->password, $user->password)) {
                return response()->json([
                    'message' => 'Success',
                    'user' => $user,
                    'token' => $token,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Password not valid',
                ], 200);
            }
        }
    }
}