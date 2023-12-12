<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOverAllOddInOverAllAmountLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('over_all_amount_limits', function (Blueprint $table) {
            $table->integer('over_all_odd')->after('over_all_amount_limit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('over_all_amount_limits')){
            Schema::table('over_all_amount_limits', function (Blueprint $table) {
                $table->dropColumn('over_all_odd');
            });
        }
    }
}
