<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OverAllSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('over_all_settings')->truncate();

        Schema::enableForeignKeyConstraints();
        
        DB::table('over_all_settings')->insert([
            'id' => '1',
            'over_all_amount_crypto_2d' => '10000000',
            'over_all_amount_crypto_1d' => '10000000',
            'crypto_2d_odd' => '80',
            'crypto_2d_odd' => '8',
            'over_all_amount' => '10000000',
            'over_all_amount_1d' => '10000000',
            'over_all_odd' => '80',
            'over_all_amount_3d' => '10000000',
            'over_all_odd_1d' => '7',
            'odd_3d' => '100',
            'tot_3d' => '200',
            'over_all_default_amount' => '100',
        ]);
    }
}
