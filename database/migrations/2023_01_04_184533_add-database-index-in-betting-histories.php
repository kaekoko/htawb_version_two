<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatabaseIndexInBettingHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('betting_histories', function (Blueprint $table) {
            $table->index('username');
        });
        Schema::table('betting_histories', function (Blueprint $table) {
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('betting_histories', function (Blueprint $table) {
            $table->dropIndex("betting_histories_username_index");
            $table->dropIndex("betting_histories_date_index");
        });
    }
}
