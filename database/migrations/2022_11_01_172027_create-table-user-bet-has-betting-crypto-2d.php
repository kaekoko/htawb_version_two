<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUserBetHasBettingCrypto2d extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bet_has_betting_c2d', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('betting_crypto2d_id');
            $table->bigInteger('user_bet_crypto2d_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_bet_has_betting_c2d');
    }
}
