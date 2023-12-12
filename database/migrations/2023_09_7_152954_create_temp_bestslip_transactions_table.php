<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempBestslipTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_bestslip_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('member')->nullable();
            $table->string('operator')->nullable();
            $table->string('member_code')->nullable();
            $table->string('product')->nullable();
            $table->string('provider_code')->nullable();
            $table->string('provider_name')->nullable();
            $table->string('method_name')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_line')->nullable();
            $table->integer('currency')->nullable();
            $table->integer('game_type')->nullable();
            $table->bigInteger('wager_id')->nullable();
            $table->decimal('Fee', 15, 2)->nullable();
            $table->string('game')->nullable();
            $table->string('game_round')->nullable();
            $table->decimal('valid_bet_amount', 15, 2)->nullable();
            $table->decimal('bet_amount', 15, 2)->nullable();
            $table->decimal('transaction_amount', 15, 2)->nullable();
            $table->string('transaction')->nullable();
            $table->decimal('payout_amount', 15, 2)->nullable();
            $table->longText('payout_detail')->nullable();
            $table->longText('bet_detail')->nullable();
            $table->decimal('commision_amount', 15, 2)->nullable();
            $table->decimal('jackpot_amount', 15, 2)->nullable();
            $table->date('settlement_date')->nullable();
            $table->decimal('jp_bet', 15, 2)->nullable();
            $table->decimal('balance', 15, 2)->nullable();
            $table->decimal('before_balance', 15, 2)->nullable();
            $table->integer('status')->nullable();
            $table->datetime('created_on')->nullable();
            $table->datetime('modified_on')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_bestslip_transactions');
    }
}
