<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewOverallColumnForCrypto2D extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('over_all_settings', function (Blueprint $table) {
            $table->decimal('over_all_amount_crypto_2d',20,2)->default('0')->after("over_all_odd");
            $table->integer('crypto_2d_odd')->nullable()->after("over_all_amount_crypto_2d");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('over_all_settings', function (Blueprint $table) {
            $table->dropColumn('over_all_amount_crypto_2d');
            $table->dropColumn('crypto_2d_odd');
        });
    }
}
