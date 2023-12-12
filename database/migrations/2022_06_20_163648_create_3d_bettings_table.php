<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create3dBettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bettings_3d', function (Blueprint $table) {
            $table->id();
            $table->string('bet_number');
            $table->decimal('amount',20 ,2);
            $table->integer('odd');
            $table->bigInteger('category_id');
            $table->string('date_3d');
            $table->string('section');
            $table->date('date');
            $table->tinyInteger('win')->default('0');
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
        Schema::dropIfExists('bettings_3d');
    }
}
