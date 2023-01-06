<?php

namespace Database\Factories;

use App\Models\RequestLoc;
use App\Models\Requests;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->words(3,true),
            'content' => $this->faker->words(20,true),
            'request_id' => $this->faker->randomElement(Requests::all()->pluck('id'))
        ];
    }
}
