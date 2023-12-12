<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppUpdatTwosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_updat_twos', function (Blueprint $table) {
            $table->id();
            $table->string('version_code')->nullable();
            $table->string('description')->nullable();
            $table->string('version_name')->nullable();
            $table->string('playstore')->nullable();
            $table->integer('force_update')->default(0);
            $table->integer('show_wallet')->default(0);
            $table->integer('wallet_hide_version');
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
        Schema::dropIfExists('app_updat_twos');
    }
}
