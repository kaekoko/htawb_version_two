<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateTimeInCreditHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_histories', function (Blueprint $table) {
            $table->date('date')->after('credit')->nullable();
            $table->time('time')->after('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credit_histories', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropColumn('time');
        });
    }
}
