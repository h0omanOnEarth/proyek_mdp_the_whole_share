<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Participant;
use App\Models\RequestLoc;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Util functions
    /**
     * Returns a JSON object that acts as a fail respond from a failed operation in the server.
     * Contains a status key which always defaults
     *
     * @param String $reason A string containing the explaination why the operation failed.
     * @param int $status An integer code that indicates what is the fail code, defaults to 0.
     *
     * @return JSON A JSON object holding the fail status code, and the reason why the operation fail.
     */
    private function failRespond(String $reason, int $status = 0) {
        return response()->json([
            "status" => $status, // Operation failed
            "reason" => $reason
        ], 200);
    }

    // ===   ===   ===

    //function list users
    function listUsers(Request $request){
        return response()->json(User::all(), 200);
    }

    //function list request
    function listRequest(Request $request){
        return response()->json(RequestLoc::all(), 200);
    }

    //function list news
    function listNews(Request $request){
        return response()->json(News::all(), 200);
    }

    //function untuk insert participants
    function insertParticipant(Request $request){
        $participant = Participant::create(array(
           "user_id" =>(int)$request->user_id,
           "request_id" =>(int)$request->request_id,
           "pickup" => $request->pickup,
           "status"=>(int) $request->status
        ));
        return response()->json($participant, 201);
    }

    //function list participants
    function listParticipants(Request $request){
        return response()->json(Participant::all(), 200);
    }

    //function untuk insert user baru
    function insertUser(Request $request){

        $user = User::create(array(
            "username" => $request->username,
            "password" => Hash::make($request->password),
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
        $user->password = Hash::make($request->password);
        $user->role = (int)$request->role;
        $user->save();
        return response()->json($user, 200);
    }

    function updateUserNoHash(Request $request){
        $user = User::find((int)$request->id);
        $user->username = $request->username;
        $user->full_name = $request->full_name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->email = $request->email;
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
            return $this->failRespond("The user with that username is not found!");
        }

        // Check if the user password does not match with the one in the request, then fail the login attempt.
        if (!Hash::check($request->password, $dbUser->password)) {
            return $this->failRespond("The provided password does not match.");
        }

        // The login attempt is successful, return the user object as the response.
        return response()->json([
            'status' => 1, // Operation successful
            'user' => $dbUser
        ], 200);
        // dd($dbUser->toJson());
    }

    /**
     * Attempts to register the user with the provided data. If any data is missing/unique columns value duplicated/does no comply to the rules,
     * then the register attempt is cancelled and return a respond on what cancelled the attempt. Otherwise, register to the database and return
     * the registered user.
     *
     * @return JSON A JSON response object containing the status of the operation. If the operation failed, it will contain a `reason` key,
     *              else if it is succesful, it will contain the newly created user model with the key `user`.
     */
    function registerUser(Request $request) {
        // Validate that the username is not taken
        $dbUser = User::where('username', $request->username)->first();
        if (isset($dbUser)) {
            return $this->failRespond("That username is taken!");
        }

        // Validate that the full name is not taken
        $dbUser = User::where('full_name', $request->full_name)->first();
        if (isset($dbUser)) {
            return $this->failRespond("That full name is taken!");
        }

        // Validate that the email is a valid email syntax
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return $this->failRespond("That email is invalid!");
        }

        // Validate that the email is not taken
        $dbUser = User::where('email', $request->email)->first();
        if (isset($dbUser)) {
            return $this->failRespond("That email is taken!");
        }

        // Register the user into the database if all rules have been complied.
        $user = User::create(array(
            "username" => $request->username,
            "password" => Hash::make($request->password),
            "full_name" => $request->full_name,
            "phone"=> $request->phone,
            "address"=> $request->address,
            "email"=> $request->email,
            "role"=> (int)$request->role
        ));

        return response()->json([
            "status" => 1, // Operation successful
            "user" => $user
        ], 201);
    }
}
