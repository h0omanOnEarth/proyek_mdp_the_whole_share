<?php

namespace Database\Factories;

use App\Helpers\ParticipantStatuses;
use App\Helpers\UserRoles;
use App\Models\Requests;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ParticipantsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Randomize the status of the participant's packets and determine who will carry the packet.
        // The available participant statuses can be seen in the ParticipantStatuses helper class.
        $participantStatus = rand(0, count(ParticipantStatuses::STATUSES) - 1);
        if ($participantStatus == 0) {
            $courier_id = null;
        } else {
            // Set the courier to the request.
            // The available user roles can be seen in the UserRoles helper class.
            $couriers = User::where('role', UserRoles::COURIER)->get();
            $randomCourier = rand(0, $couriers->count() - 1);
            $courier_id = $couriers[$randomCourier]->id;
        }

        // Return a new participant issued by a user with the user role.
        return [
            'user_id' => $this->faker->randomElement(User::where('role', 1)->get()->pluck('id')),
            'request_id' => $this->faker->randomElement(Requests::all()->pluck('id')),
            'courier_id' => $courier_id,
            'pickup' => $this->faker->address(),
            'note' => $this->faker->words(20,true),
            'status' => $participantStatus
        ];
    }
}
