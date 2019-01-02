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
            $user->api_token = base64_encode(str_random(40));
            $user->save();
            return response()->json($user->api_token, 201);
        }

        return response()->json(["status" => "fail"], 404);
    }

    public function register(Request $request) {
        $this->validateRequest($request);
        $user = User::create([
            'email' => $request->get('email'),
            'password'=> Hash::make($request->get('password')),
            'api_token' => base64_encode(str_random(40))
        ]);
        return response()->json(['status' => "success", "user_id" => $user->id, "api_token" => $user->api_token], 201);
    }

    public function validateRequest(Request $request) {
        $rules = [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5'
        ];
        $this->validate($request, $rules);
    }
}