<?php

namespace Database\Seeders;

use App\Helpers\RequestStatuses;
use App\Models\News;
use App\Models\Requests;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0");
        DB::table("news")->truncate();
        DB::statement("SET FOREIGN_KEY_CHECKS=1");

        $requests = Requests::where('status', RequestStatuses::FINISHED)->get();

        News::factory()->count(sizeof($requests))->create();
    }
}
