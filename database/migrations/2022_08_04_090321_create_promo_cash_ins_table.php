<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoCashInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_cash_ins', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('percent');
            $table->string('promo_code');
            $table->integer('turnover');
            $table->integer('lvl');
            $table->integer('status')->default(0);
            $table->string('game_text')->nullable();
            $table->string('image')->nullable();
            $table->text('rule')->nullable();
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
        Schema::dropIfExists('promo_cash_ins');
    }
}
