<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNumberSetting1dsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('number_setting_1ds', function (Blueprint $table) {
            $table->id();
            $table->string('block_number')->nullable();
            $table->string('section')->nullable();
            $table->string('type')->nullable();
            $table->string('hot_number')->nullable();
            $table->string('hot_amount')->nullable();
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
        Schema::dropIfExists('number_setting_1ds');
    }
}
