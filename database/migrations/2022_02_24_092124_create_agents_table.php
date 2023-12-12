<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('super_admin_id')->nullable();
            $table->bigInteger('senior_agent_id')->nullable();
            $table->bigInteger('master_agent_id')->nullable();
            $table->string('name');
            $table->string('phone');
            $table->string('password');
            $table->string('image')->nullable();
            $table->integer('percent');
            $table->integer('role')->default('4');
            $table->bigInteger('city_id');
            $table->decimal('credit', 20, 2)->default('0');
            $table->integer('commission')->default('0');
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
        Schema::dropIfExists('agents');
    }
}
