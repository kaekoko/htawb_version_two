<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdateTimeInSpinWheelUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spin_wheel_users', function (Blueprint $table) {
            $table->timestamp('update_time')->nullable()->after('count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spin_wheel_users', function (Blueprint $table) {
            $table->dropColumn('update_time');
        });
    }
}
