<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('en_US'); // Create the faker object based on the United States locale provider.

        // Create the admin accounts
        for ($i = 1; $i <= 2; $i++) {
            $newUser = new User();
            $newUser->username = "admin" . $i;
            $newUser->password = Hash::make("admin");
            $newUser->email = $faker->email();
            $newUser->full_name = $faker->name();
            $newUser->phone = $faker->e164PhoneNumber();
            $newUser->address = $faker->address();
            $newUser->role = 2; // Admin role
            $newUser->save();
        }

        // Create the courier accounts
        for ($i = 1; $i <= 4; $i++) {
            $newUser = new User();
            $newUser->username = "kurir" . $i;
            $newUser->password = Hash::make("password");
            $newUser->email = $faker->email();
            $newUser->full_name = $faker->name();
            $newUser->phone = $faker->phoneNumber();
            $newUser->address = $faker->address();
            $newUser->role = 3; // Courier role
            $newUser->save();
        }

        // Create the user accounts
        for ($i = 1; $i <= 4; $i++) {
            $newUser = new User();
            $newUser->username = "user" . $i;
            $newUser->password = Hash::make("password");
            $newUser->email = $faker->email();
            $newUser->full_name = $faker->name();
            $newUser->phone = $faker->phoneNumber();
            $newUser->address = $faker->address();
            $newUser->role = 1; // User role
            $newUser->save();
        }
    }
}
