<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVocherCodeInCashIn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_ins', function (Blueprint $table) {
            $table->string('voucher_code')->nullable()->after('transaction_id')->unique();
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
            $table->dropColumn('voucher_code');
        });
    }
}
