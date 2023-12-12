<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOverAllOdd1dInOverAllSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('over_all_settings', function (Blueprint $table) {
            $table->integer('over_all_odd_1d')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('over_all_settings', function (Blueprint $table) {
            $table->integer('over_all_odd_1d')->nullable();
        });
    }
}
