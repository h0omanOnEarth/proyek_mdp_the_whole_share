<?php

namespace Database\Seeders;

use App\Models\Participant;
use App\Models\Participants;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticipantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0");
        DB::table("participants")->truncate();
        DB::statement("SET FOREIGN_KEY_CHECKS=1");
        Participants::factory()->count(30)->create();
    }
}
