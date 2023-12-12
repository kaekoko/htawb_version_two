<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TempBetSlipTransactionIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_bestslip_transactions', static function (Blueprint $table) {
            $schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound  = $schemaManager->listTableIndexes('temp_bestslip_transactions');
         
            if (! array_key_exists('temp_bestslip_transactions_user_code_index', $indexesFound)) {
                $table->index('transaction', 'temp_bestslip_transactions_user_code_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_bestslip_transactions', static function (Blueprint $table) {
            $schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound  = $schemaManager->listTableIndexes('temp_bestslip_transactions');
         
            if (array_key_exists('temp_bestslip_transactions_user_code_index', $indexesFound)) {
                $table->dropIndex('temp_bestslip_transactions_user_code_index');
            }
        });
    }
}
