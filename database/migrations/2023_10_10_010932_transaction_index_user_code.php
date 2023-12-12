<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TransactionIndexUserCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('betslip_transactions', static function (Blueprint $table) {
            $schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound  = $schemaManager->listTableIndexes('betslip_transactions');
         
            if (! array_key_exists('bestslip_transactions_user_code_index', $indexesFound)) {
                $table->index('transaction', 'bestslip_transactions_user_code_index');
            }
            if (! array_key_exists('bestslip_transactions_created_at_index', $indexesFound)) {
                $table->index('transaction', 'bestslip_transactions_created_at_index');
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
        Schema::table('betslip_transactions', static function (Blueprint $table) {
            $schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound  = $schemaManager->listTableIndexes('betslip_transactions');
         
            if (array_key_exists('bestslip_transactions_created_at_index', $indexesFound)) {
                $table->dropIndex('bestslip_transactions_created_at_index');
            }
        });
    }
}
