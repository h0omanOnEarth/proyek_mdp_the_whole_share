<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    //function list users
    function listUsers(Request $request){
        return response()->json(User::all(), 200);
    }

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

    function updateUser(Request $request){
        $user = User::find((int)$request->id);
        $user->username = $request->username;
        $user->full_name = $request->full_name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->role = (int)$request->role;
        $user->save();
        return response()->json($user, 200);
    }

}
