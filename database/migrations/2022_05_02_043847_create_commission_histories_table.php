<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('senior_agent_id')->nullable();
            $table->decimal('senior_agent_commission', 20, 2)->nullable();
            $table->bigInteger('master_agent_id')->nullable();
            $table->decimal('master_agent_commission', 20, 2)->nullable();
            $table->bigInteger('agent_id')->nullable();
            $table->decimal('agent_commission', 20, 2)->nullable();
            $table->bigInteger('user_id');
            $table->bigInteger('bet_slip_id');
            $table->string('section');
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
        Schema::dropIfExists('commission_histories');
    }
}
