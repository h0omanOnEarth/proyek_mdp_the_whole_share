<?php

namespace App\Http\Controllers;

use App\Models\RequestLoc;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    //function list users
    function listUsers(Request $request){
        return response()->json(User::all(), 200);
    }

    //function list request
    function listRequest(Request $request){
        return response()->json(RequestLoc::all(), 200);
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

    function addrequest(Request $request){

        $requestloc = RequestLoc::create(array(
            "location" => $request->location,
            "batch" => (int)$request->batch,
            "deadline" => $request->deadline,
            "note"=> $request->note,
            "status"=> $request->status
        ));
        return response()->json($requestloc, 201);

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

    /**
     * Attempts to log in the user with the credentials provided by the request object.
     * Searches for the appropriate username matching with the one in the request object,
     * if found, then match the hashed password with the one in the request object.
     *
     * @return JSON A JSON object containing the response of the login attempt,
     *              if successful, contains the user object, otherwise the reason why the login attempt failed.
     *              Always contain a status code (0 for failed, 1 for successful) indicating the attempt status.
     */
    function loginUser(Request $request) {
        $dbUser = User::where('username', $request->username)->first();

        // Check if the user with the targeted username is not found, then fail the login attempt.
        if (!isset($dbUser)) {
            return response()->json([
                "status" => 0,
                "reason" => "The user with that username is not found!"
            ], 200);
        }

        // Check if the user password does not match with the one in the request, then fail the login attempt.
        if (!Hash::check($request->password, $dbUser->password)) {
            return response()->json([
                "status" => 0,
                "reason" => "The provided password does not match."
            ], 200);
        }

        // The login attempt is successful, return the user object as the response.
        return response()->json([
            'status' => 1,
            'user' => $dbUser
        ], 200);
        // dd($dbUser->toJson());
    }
}
