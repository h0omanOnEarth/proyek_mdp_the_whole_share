<?php

namespace App\Http\Controllers;

use App\Helpers\ParticipantStatuses;
use App\Helpers\RequestStatuses;
use App\Helpers\UserRoles;
use App\Models\Participants;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourierController extends Controller
{
    // === Helper Functions ===

    /**
     * Check the request object to see if there is a user id parameter in it, and whether it is the appropriate role.
     *
     * @return Boolean Return true if the request contains a valid user id, otherwise return false.
     */
    private function checkUserIsValid(Request $request, Int $role = 1)
    {
        if (!isset($request->user_id)) return false;

        // Get the user with the given id, and look for its role and compare it with the given parameter of this function.
        $targetUser = User::find($request->user_id);
        if ($targetUser->role != $role) return false;

        return true;
    }

    /**
     * Returns a JSON object that acts as a fail respond from a failed operation in the server.
     * Contains a status key which always defaults to 0.
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

    // === End of Helper Functions ===

    /**
     * Return the count result of the available packets that has not been taken by any courier.
     *
     * @return String The number of packets that is available to be taken by a courier.
     */
    public function countAvailablePackets(Request $request)
    {
        // Fetch all participants that has not been handled by a courier yet.
        $countParticipants = Participants::where('participants.courier_id', null)
            ->join('requests', 'requests.id' , '=', 'participants.request_id')
            ->where('requests.status', RequestStatuses::PENDING)
            ->count();

        return "$countParticipants";
    }

    /**
     * Return the count result of the ongoing packets that is taken by the current authenticated courier from the application.
     * The request fails if either the request is missing the user id or is not a courier.
     *
     * @return String The number of packets that is currently taken and ongoing by the authenticated courier, or a string response if fails.
     */
    public function countOngoingPackets(Request $request)
    {
        if (!$this->checkUserIsValid($request, UserRoles::COURIER)) return "Invalid User Request!";

        $countParticipants = Participants::where('participants.courier_id', $request->user_id)
            ->where('participants.status', 1)
            ->count();

        return "$countParticipants";
    }

    /**
     * Return the count result of the cancelled packets that has been taken by the current authenticated courier from the application.
     * The request fails if either the request is missing the user id or is not a courier.
     *
     * @return String The number of packets that is has been taken and cancelled by the authenticated courier, or a string response if fails.
     */
    public function countCancelledPackets(Request $request)
    {
        if (!$this->checkUserIsValid($request, UserRoles::COURIER)) return "Invalid User Request!";

        $countParticipants = Participants::where('participants.courier_id', $request->user_id)
            ->where('participants.status', 3)
            ->count();

        return "$countParticipants";
    }

    /**
     * Return the count result of the finished packets that has been taken by the current authenticated courier from the application.
     * The request fails if either the request is missing the user id or is not a courier.
     *
     * @return String The number of packets that is has been taken and delivered by the authenticated courier, or a string response if fails.
     */
    public function countFinishedPackets(Request $request)
    {
        if (!$this->checkUserIsValid($request, UserRoles::COURIER)) return "Invalid User Request!";

        $countParticipants = Participants::where('participants.courier_id', $request->user_id)
            ->where('participants.status', 2)
            ->count();

        return "$countParticipants";
    }

    /**
     * Fetch all ongoing packages that needs to be delivered by the current authenticated courier. If the request is an invalid request (not complying to the rules),
     * return an array of a single JSON object containing the reason why it failed.
     */
    public function getOngoingPackets(Request $request)
    {
        if (!$this->checkUserIsValid($request, UserRoles::COURIER))
            return response()->json([
                [
                    'status' => 0, // Fail status
                    'reason' => "Invalid user request!"
                ]
            ]);

        $packages = DB::table('participants')
            ->join('users', 'users.id', '=', 'participants.user_id')
            ->selectRaw('participants.id, participants.user_id, participants.request_id, participants.pickup, users.full_name')
            ->where('participants.courier_id', $request->user_id)
            ->where('participants.status', ParticipantStatuses::DELIVERING)
            ->get();

        return response()->json($packages);
    }

    /**
     * Fetch all delivered packages that needs to be delivered by the current authenticated courier. If the request is an invalid request (not complying to the rules),
     * return an array of a single JSON object containing the reason why it failed.
     */
    public function getDeliveredPackets(Request $request)
    {
        if (!$this->checkUserIsValid($request, UserRoles::COURIER))
            return response()->json([
                [
                    'status' => 0, // Fail status
                    'reason' => "Invalid user request!"
                ]
            ]);

        $packages = DB::table('participants')
            ->join('users', 'users.id', '=', 'participants.user_id')
            ->selectRaw('participants.id, participants.user_id, participants.request_id, participants.pickup, users.full_name')
            ->where('participants.courier_id', $request->user_id)
            ->where('participants.status', ParticipantStatuses::DELIVERED)
            ->get();

        return response()->json($packages);
    }

    /**
     * Update a participant's package to the status provided by the request parameter. Checks if the request is coming fron an authenticated courier,
     * when the check fails, return a failed response containing the reason, otherwise return a sucessful message.
     */
    public function updatePacketStatus(Request $request) {
        if (!$this->checkUserIsValid($request, UserRoles::COURIER))
            return $this->failRespond("Invalid user request!");

        // Checks if the request has the target participant id, if don't, fail the request.
        if (!isset($request->participant_id))
            return $this->failRespond("Does not have a target package id");

        // Get the targeted participant based on the provided id. If a participant is not found, fail the request.
        $participant = Participants::where('id', $request->participant_id)->first();
        if (!isset($participant))
            return $this->failRespond("Invalid package id!");

        // Update the found participant status.
        $participant->status = $request->new_status;
        $participant->save();

        return response()->json([
            "status" => 1, // Success status
            "message" => "Package status successfully updated!"
        ]);
    }
}
