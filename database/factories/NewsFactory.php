<?php

namespace Database\Factories;

use App\Helpers\RequestStatuses;
use App\Models\RequestLoc;
use App\Models\Requests;
use GuzzleHttp\Psr7\Request;
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

        $requests = Requests::where('status', RequestStatuses::FINISHED)->get();

        return [
            'title' => $this->faker->words(3,true),
            'content' => $this->faker->words(20,true),
            'request_id' => $this->faker->unique()->randomElement($requests->pluck('id'))
        ];
    }
}
