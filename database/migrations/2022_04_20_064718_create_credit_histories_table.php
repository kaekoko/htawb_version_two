<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('super_admin_id')->nullable();
            $table->bigInteger('senior_agent_id')->nullable();
            $table->bigInteger('master_agent_id')->nullable();
            $table->bigInteger('agent_id')->nullable();
            $table->bigInteger('payment_method_id');
            $table->decimal('credit', 20, 2);
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
        Schema::dropIfExists('credit_histories');
    }
}
