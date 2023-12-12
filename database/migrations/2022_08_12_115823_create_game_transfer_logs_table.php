<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameTransferLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_transfer_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('referenceid');
            $table->string('p_code');
            $table->string('type');
            $table->string('message');
            $table->string('error_code');
            $table->string('amount')->nullable();
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
        Schema::dropIfExists('game_transfer_logs');
    }
}
