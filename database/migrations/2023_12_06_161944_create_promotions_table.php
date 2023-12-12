<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('cashin_amount')->nullable();
            $table->string('cashout_amount')->nullable();
            $table->string('two_amount')->nullable();
            $table->string('three_amount')->nullable();
            $table->string('four_amount')->nullable();
            $table->string('promotion_amount')->nullable();
            $table->string('status')->default('0');
            $table->string('precent')->nullable();
            $table->string('total_bet')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('promotions');
    }
}
