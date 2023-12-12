<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToCashInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_ins', function (Blueprint $table) {
            $table->string('account_name')->nullable()->after('payment_id');
            $table->bigInteger('transaction_id')->nullable()->after('account_name');
            $table->string('user_phone')->nullable()->after('transaction_id');
            $table->text('message')->nullable()->after('user_phone');
            $table->enum('status', ['Pending', 'Reject', 'Approve'])->default('Pending')->after('message');
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
            //
        });
    }
}
