<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RequestsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'location' => $this->faker->address(),
            'batch' => 1,
            'deadline' => $this->faker->dateTimeBetween('+1 week','+3 week'),
            'note' => $this->faker->words(20,true),
            'status'=>  $this->faker->randomElement(['Pending','Sent','Finished'])
        ];
    }
}
