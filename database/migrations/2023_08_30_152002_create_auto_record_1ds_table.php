<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutoRecord1dsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_record_1ds', function (Blueprint $table) {
            $table->id();
            $table->date('record_date')->nullable();
            $table->string('record_time')->nullable();
            $table->string('twod_number')->nullable();
            $table->string('buy')->nullable();
            $table->string('sell')->nullable();
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
        Schema::dropIfExists('auto_record_1ds');
    }
}
