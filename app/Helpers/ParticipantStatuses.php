<?php

namespace App\Helpers;

/**
 * A class containing the available participant statuses that can be used in the application.
 * Only contains static properties that holds an equivalent integer values for that status, and an array of those values.
 */
class ParticipantStatuses {
    public const PENDING = 0;
    public const DELIVERING = 1;
    public const DELIVERED = 2;
    public const CANCELLED = 3;

    /**
     * An array comprised of all the available statuses, usually used to randomize a participant packet status.
     */
    public const STATUSES = [
        ParticipantStatuses::PENDING,
        ParticipantStatuses::DELIVERING,
        ParticipantStatuses::DELIVERED,
        ParticipantStatuses::CANCELLED,
    ];
}
