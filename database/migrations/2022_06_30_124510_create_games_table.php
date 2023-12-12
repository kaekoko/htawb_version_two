<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('g_code')->nullable();
            $table->string('name')->nullable();
            $table->integer('provider_id');
            $table->integer('category_id');
            $table->string('html_type')->nullable();
            $table->integer('active')->default(0);
            $table->string('img')->nullable();
            $table->integer('is_hot')->default(0);
            $table->integer('is_new')->default(0);
            $table->integer('is_gamepage')->default(0);
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
        Schema::dropIfExists('games');
    }
}
