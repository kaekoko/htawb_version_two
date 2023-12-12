<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShamelessGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shameless_games', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('g_code');
            $table->string('html_type');
            $table->boolean('active')->default(0);
            $table->boolean('is_hot')->default(0);
            $table->boolean('is_new')->default(0);
            $table->string('image')->nullable();
            $table->string('cate_code')->nullable();
            $table->string('p_code')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shameless_games');
    }
}
