<?php

namespace App\Http\Controllers;

use App\User;
use App\Radar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RadarController extends Controller
{
    public function getAllRadars(Request $request)
    {
        $radars = Radar::All();

        if ($radars != null)
            return response()->json(["status" => "success", "data" => $radars], 201);
        else
            return response()->json(["status" => "fail"], 404);
    }

    public function addRadar(Request $request) {

        $user = User::where("api_token", $request->get("api_token"))->first();

        if(!$user || $user->canAddRadars != 1)
            return response()->json(['status' => "fail"], 404);

        $this->validateRequest($request);

        $lat = $request->get("lat");
        $long = $request->get("long");
        $name = $request->get("name", "Radar");
        $speed = $request->get("speed_limit", 0);
        $type = $request->get("type");

        $radar = Radar::create([
            'name' => $name,
            'speed_limit'=> $speed,
            'type' => $type,
            'lat' => $lat,
            'long' => $long
        ]);
        return response()->json(['status' => "success", "radar" => $radar], 201);
    }

    public function deleteRadar(Request $request) {

        $user = User::where("api_token", $request->get("api_token"))->first();

        if(!$user || $user->isAdmin != 1)
            return response()->json(['status' => "fail"], 404);

        $id = $request->get("id", -1);

        if($id == -1)
            return response()->json(['status' => "fail"], 404);

        Radar::destroy($id);

        return response()->json(['status' => "success"], 201);
    }

    public function addToCount(Request $request)
    {
        $user = User::where("api_token", $request->get("api_token"))->first();

        if(!$user || $user->canAddRadars != 1)
            return response()->json(['status' => "fail"], 404);

        $id = $request->get("radar_id", -1);

        if($id == -1)
            return response()->json(['status' => "fail"], 404);

        DB::table('radars')->where('id', $id)->increment('count');

        return response()->json(['status' => "success"], 201);
    }

    public function validateRequest(Request $request) {
        $rules = [
            'lat' => 'required',
            'long' => 'required',
            'type' => 'required',
            'api_token' => 'required'
        ];
        $this->validate($request, $rules);
    }
}