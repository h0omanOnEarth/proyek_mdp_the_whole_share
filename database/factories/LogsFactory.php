<?php

namespace Database\Factories;

use App\Models\RequestLoc;
use App\Models\Requests;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class LogsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomElement(User::all()->pluck('id')),
            'request_id' => $this->faker->randomElement(Requests::all()->pluck('id')),
            'content' => $this->faker->words(20,true)
        ];
    }
}
