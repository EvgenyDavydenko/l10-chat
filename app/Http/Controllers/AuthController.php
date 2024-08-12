<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // REGISTER API
    public function register(Request $request)
    {
        // validation
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed"
        ]);

        // create data
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        // send response
        return response()->json([
            "status" => true,
            "message" => "User registered succesfully"
        ], 201);
    }

    // LOGIN API
    public function login(Request $request)
    {
        // validation
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // check student
        $user = User::where("email", "=", $request->email)->first();

        if (isset($user->id)) {

            if (Hash::check($request->password, $user->password)) {

                // create a token
                $token = $user->createToken("auth_token")->plainTextToken;

                /// send a response
                return response()->json([
                    "status" => true,
                    "message" => "User logged in successfully",
                    "user_id" => $user->id,
                    "access_token" => $token
                ]);
            } else {

                return response()->json([
                    "status" => false,
                    "message" => "Password didn't match"
                ], 404);
            }
        } else {

            return response()->json([
                "status" => 0,
                "message" => "User not found"
            ], 404);
        }
    }

    // PROFILE API
    public function profile()
    {
        return response()->json([
            "status" => 1,
            "message" => "User Profile information",
            "data" => auth()->user()
        ]);
    }

    // LOGOUT API
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "status" => 1,
            "message" => "User logged out successfully"
        ]);
    }
}