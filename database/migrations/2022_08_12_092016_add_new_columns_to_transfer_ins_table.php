<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToTransferInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_ins', function (Blueprint $table) {
            $table->string('referenceid')->after('user_id');
            $table->string('error_code')->after('referenceid')->nullable();
            $table->string('message')->after('error_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_ins', function (Blueprint $table) {
            //
        });
    }
}
