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
            return response()->json(["status" => "success", "api_token" => $user->api_token, "admin" => $user->isAdmin], 201);
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
        return response()->json(['status' => "success", "user_id" => $user->id, "api_token" => $user->api_token, "admin" => 0], 201);
    }

    public function changePrivileges(Request $request) {
        $user = User::where("api_token", $request->get("api_token"))->first();

        if(!$user || $user->isAdmin != 1)
            return response()->json(['status' => "fail"], 404);

        $userid = $request->get("user_id", -1);
        $privilege = $request->get("privilege", -1);

        if($userid == -1 || $privilege == -1 || $privilege > 1)
            return response()->json(['status' => "fail"], 404);

        $usertochange = User::where("user_id", $userid)->first();
        $usertochange->canAddRadars = $privilege;
        $usertochange->save();

        return response()->json(['status' => "success"], 201);
    }

    public function getAllUsers(Request $request)
    {
        $user = User::where("api_token", $request->get("api_token"))->first();

        if(!$user || $user->isAdmin != 1)
            return response()->json(['status' => "fail"], 404);

        $users = User::All();

        if ($users != null)
            return response()->json(["status" => "success", "data" => $users], 201);
        else
            return response()->json(["status" => "fail"], 404);
    }


    public function validateRequest(Request $request) {
        $rules = [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5'
        ];
        $this->validate($request, $rules);
    }
}