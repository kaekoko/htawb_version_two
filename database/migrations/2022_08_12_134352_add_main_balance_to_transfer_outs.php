<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMainBalanceToTransferOuts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_outs', function (Blueprint $table) {
            $table->decimal('main_balance', 20, 2)->after('game_balance')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_outs', function (Blueprint $table) {
            $table->dropColumn('main_balance');
        });
    }
}
