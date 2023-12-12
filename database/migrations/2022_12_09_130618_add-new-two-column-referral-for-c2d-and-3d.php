<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewTwoColumnReferralForC2dAnd3d extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('over_all_settings', function (Blueprint $table) {
            $table->decimal('referral_c2d', 20, 2)->after('referral')->default(0);
            $table->decimal('referral_3d', 20, 2)->after('referral_c2d')->default(0);
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
            $table->dropColumn('referral_c2d');
            $table->dropColumn('referral_3d');
        });
    }
}
