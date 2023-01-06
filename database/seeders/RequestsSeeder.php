<?php

namespace Database\Seeders;

use App\Models\RequestLoc;
use App\Models\Requests;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0");
        DB::table("requests")->truncate();
        DB::statement("SET FOREIGN_KEY_CHECKS=1");
        Requests::factory()->count(10)->create();
    }
}
