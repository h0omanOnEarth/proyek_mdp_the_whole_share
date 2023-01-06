<?php

namespace Database\Seeders;

use App\Models\Log;
use App\Models\Logs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0");
        DB::table("logs")->truncate();
        DB::statement("SET FOREIGN_KEY_CHECKS=1");
        Logs::factory()->count(10)->create();
    }
}
