<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrypto1dBettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crypto1d_bettings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bet_id');
            $table->string('bet_number');
            $table->decimal('amount',20 ,2);
            $table->integer('odd');
            $table->bigInteger('category_id');
            $table->string('section');
            $table->date('date')->nullable();
            $table->tinyInteger('win')->default('0');
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
        Schema::dropIfExists('crypto1d_bettings');
    }
}
