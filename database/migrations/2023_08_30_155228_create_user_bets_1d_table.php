<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBets1dTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bets_1d', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('section');
            $table->integer('read')->default('0');
            $table->integer('reward')->default('0');
            $table->integer('reward_amount')->default('0');
            $table->integer('total_amount')->default('0');
            $table->date('date')->nullable();
            $table->integer('total_bet')->default('0');
            $table->integer('win')->default('0');
            $table->integer('claim')->default('0');
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
        Schema::dropIfExists('user_bets_1d');
    }
}
