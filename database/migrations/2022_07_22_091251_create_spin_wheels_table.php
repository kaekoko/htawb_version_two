<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpinWheelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spin_wheels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('percent')->nullable();
            $table->string('image')->nullable();
            $table->integer('degree')->nullable();
            $table->decimal('amount', 20, 2)->default(0);
            $table->string('type')->nullable();
            $table->text('message')->nullable();
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
        Schema::dropIfExists('spin_wheels');
    }
}
