<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('name');
            $table->integer('cash_in_status')->default(0)->after('logo');
            $table->integer('cash_out_status')->default(0)->after('cash_in_status');
            $table->enum('type', ['banking', 'pay', 'mobile-topup'])->default('pay')->after('cash_out_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            //
        });
    }
}
