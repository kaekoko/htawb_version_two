<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllStaticInBetDate3dHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bet_date_3d_histories', function (Blueprint $table) {
            $table->bigInteger('all_bet_amount')->default(0)->after('bet_date');
            $table->bigInteger('total_reward')->default(0)->after('all_bet_amount');
            $table->bigInteger('profit')->default(0)->after('total_reward');
            $table->bigInteger('user_referral')->default(0)->after('profit');
            $table->string('lucky_number')->nullable()->after('user_referral');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bet_date_3d_histories', function (Blueprint $table) {
            $table->dropColumn('all_bet_amount');
            $table->dropColumn('total_reward');
            $table->dropColumn('profit');
            $table->dropColumn('user_referral');
            $table->dropColumn('lucky_number');
        });
    }
}
