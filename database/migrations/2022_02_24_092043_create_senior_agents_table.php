<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeniorAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('senior_agents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('super_admin_id')->nullable();
            $table->string('name');
            $table->string('phone');
            $table->string('password');
            $table->string('image')->nullable();
            $table->integer('percent');
            $table->integer('role')->default('2');
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
        Schema::dropIfExists('senior_agents');
    }
}
