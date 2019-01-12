<?php

namespace App\Http\Controllers;

use App\User;
use App\Radar;
use Illuminate\Http\Request;

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
}