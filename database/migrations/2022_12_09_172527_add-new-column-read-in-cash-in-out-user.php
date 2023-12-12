<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnReadInCashInOutUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_ins', function (Blueprint $table) {
            $table->integer('read')->after('id')->default('0');
        });

        Schema::table('cash_outs', function (Blueprint $table) {
            $table->integer('read')->after('id')->default('0');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('read')->after('id')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_ins', function (Blueprint $table) {
            $table->dropColumn('read');
        });

        Schema::table('cash_outs', function (Blueprint $table) {
            $table->dropColumn('read');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('read');
        });
    }
}
