<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameCategoryGameProviderPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_category_game_provider', function (Blueprint $table) {  
            $table->unsignedBigInteger('shameless_game_provider_id');
            $table->foreign('shameless_game_provider_id', 'shameless_game_provider_id_fk_8603315')->references('id')->on('shameless_game_providers')->onDelete('cascade');
            $table->unsignedBigInteger('shameless_game_category_id');
            $table->foreign('shameless_game_category_id', 'shameless_game_category_id_fk_8603315')->references('id')->on('shameless_game_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_category_game_provider');
    }
}
