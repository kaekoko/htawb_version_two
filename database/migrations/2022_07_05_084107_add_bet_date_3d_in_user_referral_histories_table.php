<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBetDate3dInUserReferralHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_referral_histories', function (Blueprint $table) {
            $table->date('bet_date_3d')->after('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_referral_histories', function (Blueprint $table) {
            $table->dropColumn('bet_date_3d');
        });
    }
}
