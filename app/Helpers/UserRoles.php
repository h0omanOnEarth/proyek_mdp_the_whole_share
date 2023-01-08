<?php

namespace App\Helpers;

/**
 * A class containing the available user roles that can be used in the application.
 * Only contains static properties that holds an equivalent integer values for that role, and an array of those values.
 */
class UserRoles {
    public const USER = 1;
    public const ADMIN = 2;
    public const COURIER = 3;

    /**
     * An array comprised of all the available roles, usually used to randomize a user role.
     */
    public const ROLES = [
        UserRoles::USER,
        UserRoles::ADMIN,
        UserRoles::COURIER,
    ];
}
