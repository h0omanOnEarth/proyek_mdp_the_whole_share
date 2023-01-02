<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    //function untuk insert user baru
    function insertUser(Request $request){

        $user = User::create(array(
            "username" => $request->username,
            "password" => $request->password,
            "full_name" => $request->full_name,
            "phone"=> $request->phone,
            "address"=> $request->address,
            "email"=> $request->email,
            "role"=> (int)$request->role
        ));
        return response()->json($user, 201);

    }

}
