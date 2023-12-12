<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->decimal('balance', 20, 2);
            $table->decimal('game_balance', 20, 2)->nullable();
            $table->decimal('transfer_balance', 20, 2);
            $table->string('referenceid');
            $table->string('message')->nullable();
            $table->string('error_code')->nullable();
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
        Schema::dropIfExists('promotion_logs');
    }
}
