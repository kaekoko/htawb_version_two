<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotInBettings3dTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bettings_3d', function (Blueprint $table) {
            $table->integer('tot')->default(0)->after('win');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bettings_3d', function (Blueprint $table) {
            $table->dropColumn('tot');
        });
    }
}
