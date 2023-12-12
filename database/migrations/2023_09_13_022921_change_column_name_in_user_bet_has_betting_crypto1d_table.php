<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnNameInUserBetHasBettingCrypto1dTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bet_has_betting_crypto1d', function (Blueprint $table) {
            $table->renameColumn('betting_crypto2d_id', 'betting_crypto1d_id');
            $table->renameColumn('user_bet_crypto2d_id', 'user_bet_crypto1d_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bet_has_betting_crypto1d', function (Blueprint $table) {
            //
        });
    }
}
