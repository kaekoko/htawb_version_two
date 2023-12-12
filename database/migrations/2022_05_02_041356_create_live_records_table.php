<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_records', function (Blueprint $table) {
            $table->id();
            $table->string('buy')->nullable();
            $table->string('sell')->nullable();
            $table->string('twod')->nullable();
            $table->datetime('time')->nullable();
            $table->integer('request_count')->default(0);
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
        Schema::dropIfExists('live_records');
    }
}
