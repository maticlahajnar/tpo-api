<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->get("email");
        $password = $request->get("password");

        $user = User::where("email", $email)->first();

        if($user && Hash::check($password, $user->password))
        {
            return response()->json($user, 201);
        }

        return response()->json(["status" => "fail", "user" => $user], 404);
    }

    public function register(Request $request) {
        $this->validateRequest($request);
        $user = User::create([
            'email' => $request->get('email'),
            'password'=> Hash::make($request->get('password')),
            'api_token' => str_random(32)
        ]);
        return response()->json(['status' => "success", "user_id" => $user->id], 201);
    }

    public function validateRequest(Request $request) {
        $rules = [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5'
        ];
        $this->validate($request, $rules);
    }
}