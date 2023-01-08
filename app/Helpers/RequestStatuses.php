<?php

namespace App\Helpers;

/**
 * A class containing the available donation request statuses that can be used in the application.
 * Only contains static properties that holds an equivalent string values for that status, and an array of those values.
 */
class RequestStatuses {
    public const PENDING = "Pending";
    public const SENT = "Sent";
    public const FINISHED = "Finished";

    /**
     * An array comprised of all the available statuses, usually used to randomize a request status.
     */
    public const STATUSES = [
        RequestStatuses::PENDING,
        RequestStatuses::SENT,
        RequestStatuses::FINISHED,
    ];
}
