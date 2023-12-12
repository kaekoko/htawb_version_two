<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOverAllOverAllAmountCrypto1dInOverAllSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('over_all_settings', function (Blueprint $table) {
            $table->decimal('over_all_amount_crypto_1d',20,2)->default('0');
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
            //
        });
    }
}
