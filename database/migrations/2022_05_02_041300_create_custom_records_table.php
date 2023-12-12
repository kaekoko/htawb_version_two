<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_records', function (Blueprint $table) {
            $table->id();
            $table->date('record_date')->nullable();
            $table->string('record_time')->nullable();
            $table->string('twod_number')->nullable();
            $table->string('buy')->nullable();
            $table->string('sell')->nullable();
            $table->integer('is_published')->default(0);
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
        Schema::dropIfExists('custom_records');
    }
}
