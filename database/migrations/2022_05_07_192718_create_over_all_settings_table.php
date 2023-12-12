<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOverAllSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('over_all_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('over_all_amount',20,2)->default('0');
            $table->integer('over_all_odd')->nullable();
            $table->decimal('over_all_default_amount',20,2)->default('0');
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
        Schema::dropIfExists('over_all_settings');
    }
}
