<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLuckyNumberFieldsInLuckyNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lucky_numbers', function (Blueprint $table) {
            $table->string('set')->after('lucky_number')->nullable();
            $table->string('value')->after('set')->nullable();
            $table->string('modern')->after('value')->nullable();
            $table->string('internet')->after('modern')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lucky_numbers', function (Blueprint $table) {
            $table->dropColumn('set');
            $table->dropColumn('value');
            $table->dropColumn('modern');
            $table->dropColumn('internet');
        });
    }
}
