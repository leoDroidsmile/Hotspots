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
        Schema::table('hotspots', function (Blueprint $table) {
            //
            $table->integer('Witness');
            $table->integer('Beacon');
            $table->integer('Bdirect');

            $table->integer('Witness_Invalid');
            $table->integer('Beacon_Invalid');
            $table->integer('Bdirect_Invalid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotspots', function (Blueprint $table) {
            //
        });
    }
};
