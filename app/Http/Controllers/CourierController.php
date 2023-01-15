<?php

namespace App\Http\Controllers;

use App\Helpers\ParticipantStatuses;
use App\Helpers\UserRoles;
use App\Models\Participants;
use App\Models\User;
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
     * Get packages that are waiting to be picked up by a courier. If the request is an invalid request (not complying to the rules),
     * return an array of a single JSON object containing the reason why it failed.
     *
     * @return JsonArray An array of JsonObjects containing the datas of the packages.
     */
    public function getPendingPackets(Request $request)
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
            // ->where('participants.courier_id', $request->user_id)
            ->where('participants.status', ParticipantStatuses::PENDING)
            ->get();

        return response()->json($packages);
    }

    /**
     * Fetch all ongoing packages that needs to be delivered by the current authenticated courier. If the request is an invalid request (not complying to the rules),
     * return an array of a single JSON object containing the reason why it failed.
     *
     * @return JsonArray An array of JsonObjects containing the datas of the packages.
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
     *
     * @return JsonArray An array of JsonObjects containing the datas of the packages.
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
     * Update a participant's package from `PENDING` to `DELIVERING` by the authenticated courier. Checks if the courier id is a valid id, and if the status of the package is indeed `PENDING`.
     * When the check fails, return a failed response containing the reason, otherwise return a sucessful message.
     *
     * @return JsonObject A json response containing the status of the operation and message if successful, and a reason if fails.
     *                    Contains 3 different status codes, 0 = Fail, 1 = Success, 2 = Invalid operation
     */
    public function takePackage(Request $request)
    {
        if (!$this->checkUserIsValid($request, UserRoles::COURIER))
            return $this->failRespond("Invalid user request!");

        // Checks if the request has the target participant id, if don't, fail the request.
        if (!isset($request->participant_id))
            return $this->failRespond("Does not have a target package id");

        // Get the targeted participant based on the provided id. If a participant is not found, fail the request.
        $participant = Participants::where('id', $request->participant_id)->first();
        if (!isset($participant))
            return $this->failRespond("Invalid package id!");

        // Check if the status of the package, if it is in fact not `PENDING`, then fail the request with status code of 2.
        if ($participant->status != ParticipantStatuses::PENDING)
            return $this->failRespond("Package is not pending to be taken!", 2);

        // All checks passes, update the status and courier id
        $participant->courier_id = $request->user_id;
        $participant->status = ParticipantStatuses::DELIVERING;
        $participant->save();

        return response()->json([
            "status" => 1, // Success status
            "message" => "Package status successfully updated!"
        ]);
    }

    /**
     * Update a participant's package to the status provided by the request parameter. Checks if the request is coming fron an authenticated courier,
     * when the check fails, return a failed response containing the reason, otherwise return a sucessful message.
     *
     * @return JsonObject The object containing the status of the request and the reason of failure if it fails, otherwise the values of the package.
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

    /**
     * Cancel a courier delivery when the courier requested to. Updates the package status to `Pending` again.
     *
     * @return JsonObject The response containing the status of the operation.
     */
    public function cancelPackageDelivery(Request $request)
    {
        if (!$this->checkUserIsValid($request, UserRoles::COURIER))
            return $this->failRespond("Invalid user request!");

        if (!isset($request->package_id))
            return $this->failRespond("Target package is not set!");

        // Check is the id is a valid package
        $package = Participants::where("id", $request->package_id)
            ->first();
        if ($package->status != ParticipantStatuses::DELIVERING)
            return $this->failRespond("Invalid operation on the current state of the package!");

        $package->status = ParticipantStatuses::PENDING;
        $package->courier_id = null;
        $package->save();

        return response()->json([
            "status" => 1, // Successful status
            "message" => "Successfully cancelled the delivery!"
        ]);
    }

    /**
     * Get the details of the requested packet and return it as a json object.
     *
     * @return JsonObject The object containing the status of the request and the reason of failure if it fails, otherwise the values of the package.
     */
    public function getPacketDetail(Request $request)
    {
        if (!isset($request->package_id))
            return $this->failRespond("No packet is specified!");

        $packet = DB::table('participants')
            ->join('requests', 'requests.id', '=', 'participants.request_id')
            ->join('users', 'users.id', '=', 'participants.user_id')
            ->selectRaw("participants.id, participants.pickup, users.full_name, requests.location")
            ->where('participants.id', $request->package_id)
            ->first();

        return response()->json([
            'status' => 1, // Successful status
            'detail' => $packet
        ]);
    }

    /**
     * Finish the package request by uploading image as proof that the package has been delivered, and update the status of the package.
     *
     * @return JsonObject The object containing the status of the request and the reason of failure if it fails, otherwise the values of the package.
     */
    public function finishDeliverPacket(Request $request) {
        if (!$this->checkUserIsValid($request, UserRoles::COURIER))
            return $this->failRespond("Invalid user request!");

        if ($request->file('image') == null)
            return $this->failRespond("No image has been uploaded!");

        if (!isset($request->image_name))
            return $this->failRespond("Image name not designated!");

        if (!isset($request->package_id))
            return $this->failRespond("Target package id not set!");

        $image = $request->file('image');
        $path = $image->storeAs('public/images', $request->image_name);

        if (isset($path)) {
            $participant = Participants::where('id', $request->package_id)->first();
            $participant->status = ParticipantStatuses::DELIVERED;
            $participant->save();

            return response()->json([
                'status' => 1, // Successful request
                'path' => $path
            ]);
        }
        else return $this->failRespond("Fail to store the image to the server storage!");
    }
}
