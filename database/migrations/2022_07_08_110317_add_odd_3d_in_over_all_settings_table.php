<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOdd3dInOverAllSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('over_all_settings', function (Blueprint $table) {
            $table->integer('odd_3d')->default(0)->after('over_all_odd');
            $table->integer('tot_3d')->default(0)->after('odd_3d');
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
            $table->dropColumn('odd_3d');
            $table->dropColumn('tot_3d');
        });
    }
}
