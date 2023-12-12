<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBetDateInUserBets3dTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bets_3d', function (Blueprint $table) {
            $table->date('bet_date')->after('noti_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bets_3d', function (Blueprint $table) {
            $table->dropColumn('bet_date');
        });
    }
}
