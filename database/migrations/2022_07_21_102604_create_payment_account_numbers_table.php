<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentAccountNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_account_numbers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->foreign('payment_id')->references('id')->on('payment_methods')->onDelete('cascade');
            $table->string('account_number')->nullable();
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
        Schema::dropIfExists('payment_account_numbers');
    }
}
