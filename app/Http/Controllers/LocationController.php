<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    function listLocations(Request $request){
        return response()->json(Location::all(), 200);
    }

    function insertLocation(Request $request){
        $location = Location::create(array(
            "address" => $request->address,
            "note"=> $request->note
        ));
        return response()->json($location, 201);

    }
}
