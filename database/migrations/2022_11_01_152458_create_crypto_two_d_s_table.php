<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptoTwoDSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crypto_two_ds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id');
            $table->string('bet_number');
            $table->decimal('hot_amout_limit', 20, 2)->default('0');
            $table->tinyInteger('close_number')->default('0');
            $table->decimal('default_amount', 20, 2)->default('100');
            $table->decimal('current_amount', 20, 2)->default('0');
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
        Schema::dropIfExists('crypto_two_ds');
    }
}
