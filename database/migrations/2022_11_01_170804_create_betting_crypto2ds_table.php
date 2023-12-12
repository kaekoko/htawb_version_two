<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBettingCrypto2dsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bettings_crypto_2d', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bet_id');
            $table->string('bet_number');
            $table->decimal('amount',20 ,2);
            $table->integer('odd');
            $table->bigInteger('category_id');
            $table->string('section');
            $table->tinyInteger('win')->default('0');
            $table->date('date')->nullable();
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
        Schema::dropIfExists('bettings_crypto_2d');
    }
}
