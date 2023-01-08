<?php

namespace App\Http\Controllers;

use App\Helpers\RequestStatuses;
use App\Helpers\UserRoles;
use App\Models\Participants;
use App\Models\User;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    // === Helper Functions ===

    /**
     * Check the request object to see if there is a user id parameter in it, and whether it is the appropriate role.
     */
    private function checkUserIsValid(Request $request, Int $role = 1)
    {
        if (!isset($request->user_id)) return false;

        // Get the user with the given id, and look for its role and compare it with the given parameter of this function.
        $targetUser = User::find($request->user_id);
        if ($targetUser->role != $role) return false;

        return true;
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
}
