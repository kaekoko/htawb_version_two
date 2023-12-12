<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyStaticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_statics', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('all_bet_amount');
            $table->bigInteger('total_reward');
            $table->bigInteger('profit');
            $table->bigInteger('user_referral');
            $table->date('date');
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
        Schema::dropIfExists('daily_statics');
    }
}
