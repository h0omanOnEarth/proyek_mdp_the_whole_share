<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('participants', function (Blueprint $table) {
            // Create a new column indication if this participant packet is being delivered or has been delivered by a courier.
            $table->unsignedBigInteger('courier_id')->nullable();
            $table->index('courier_id');
            $table->foreign('courier_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropForeign('courier_id');
            $table->dropIndex('courier_id');
            $table->dropColumn('courier_id');
        });
    }
};
