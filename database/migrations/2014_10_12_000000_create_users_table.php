<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('super_admin_id')->nullable();
            $table->bigInteger('senior_agent_id')->nullable();
            $table->bigInteger('master_agent_id')->nullable();
            $table->bigInteger('agent_id')->nullable();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('password');
            $table->decimal('balance', 20, 2)->nullable();
            $table->string('image')->nullable();
            $table->integer('role')->default('5');
            $table->integer('isVerified')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
