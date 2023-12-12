<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToCashOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_outs', function (Blueprint $table) {
            $table->string('account_name')->nullable()->after('payment_id');
            $table->string('user_phone')->nullable()->after('account_name');
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
        Schema::table('cash_outs', function (Blueprint $table) {
            //
        });
    }
}
