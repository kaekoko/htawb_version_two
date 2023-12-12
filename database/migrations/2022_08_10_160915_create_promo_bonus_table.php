<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoBonusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_bonus', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('promo_id');
            $table->decimal('bonus_wallet', 20, 2);
            $table->decimal('turnover', 20, 2);
            $table->dateTime('update_time');
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
        Schema::dropIfExists('promo_bonus');
    }
}
