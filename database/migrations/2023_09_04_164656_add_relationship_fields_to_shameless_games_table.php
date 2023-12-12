<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToShamelessGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shameless_games', function (Blueprint $table) {
           
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id', 'category_fk_8556556')->references('id')->on('shameless_game_categories');
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id', 'provider_fk_8556557')->references('id')->on('shameless_game_providers');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_8556509')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shameless_games', function (Blueprint $table) {
            //
        });
    }
}
