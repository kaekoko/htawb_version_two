<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToTransferOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_outs', function (Blueprint $table) {
            $table->string('referenceid')->after('user_id');
            $table->string('message')->after('referenceid')->nullable();
            $table->string('error_code')->after('message')->nullable();
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
            //
        });
    }
}
