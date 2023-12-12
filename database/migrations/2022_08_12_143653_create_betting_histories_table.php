<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBettingHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('betting_histories', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('p_win')->nullable();
            $table->string('p_share')->nullable();
            $table->string('turnover')->nullable();
            $table->string('game_id')->nullable();
            $table->string('gamename')->nullable();
            $table->string('bet')->nullable();
            $table->string('payout')->nullable();
            $table->string('commission')->nullable();
            $table->integer('status')->nullable();
            $table->string('provider_name')->nullable();
            $table->date('date')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('match_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->string('p_code')->nullable();
            $table->string('p_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('betting_histories');
    }
}
