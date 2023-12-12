<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalCommissionToCommissionHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commission_histories', function (Blueprint $table) {
            $table->decimal('total_commission', 20, 2)->after('agent_commission')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commission_histories', function (Blueprint $table) {
            $table->dropColumn('total_commission');
        });
    }
}
